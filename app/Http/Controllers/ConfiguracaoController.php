<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $configuracoes = [
            'sistema' => [
                'nome' => config('app.name'),
                'ambiente' => config('app.env'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
            ],
            'banco' => [
                'conexao' => config('database.default'),
                'versao' => $this->getDatabaseVersion(),
            ],
            'cache' => [
                'driver' => config('cache.default'),
                'status' => Cache::get('cache_status', 'Desconhecido'),
            ],
            'sessao' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
            ],
            'email' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'porta' => config('mail.mailers.smtp.port'),
            ],
        ];

        return view('configuracoes.index', compact('configuracoes'));
    }

    public function atualizar(Request $request)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'tipo' => 'required|in:sistema,email,seguranca,notificacoes',
            'configuracoes' => 'required|array',
        ]);

        // Em um sistema real, aqui atualizaríamos as configurações no banco de dados
        // ou no arquivo .env, dependendo do tipo de configuração
        
        // Para fins de exemplo, apenas simulamos a atualização
        foreach ($validated['configuracoes'] as $chave => $valor) {
            // Atualizar configuração (implementação real dependeria do sistema)
            // Config::set($chave, $valor);
        }

        // Limpar cache das configurações
        Artisan::call('config:cache');

        return back()->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function backup()
    {
        $this->authorize('admin');

        // Executar backup do banco de dados
        $backupFile = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('app/backups/' . $backupFile)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return response()->download(storage_path('app/backups/' . $backupFile))
                             ->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'Erro ao criar backup do banco de dados.');
        }
    }

    public function limparCache()
    {
        $this->authorize('admin');

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return back()->with('success', 'Cache limpo com sucesso!');
    }

    public function logs()
    {
        $this->authorize('admin');

        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return back()->with('error', 'Arquivo de log não encontrado.');
        }

        $logs = file_get_contents($logFile);
        $logsArray = array_slice(explode("\n", $logs), -100); // Últimas 100 linhas

        return view('configuracoes.logs', compact('logsArray'));
    }

    public function limparLogs()
    {
        $this->authorize('admin');

        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }

        return back()->with('success', 'Logs limpos com sucesso!');
    }

    public function sistema()
    {
        $this->authorize('admin');

        $informacoes = [
            'php' => [
                'versao' => PHP_VERSION,
                'extensoes' => get_loaded_extensions(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
            ],
            'laravel' => [
                'versao' => app()->version(),
                'ambiente' => app()->environment(),
                'timezone' => config('app.timezone'),
                'debug' => config('app.debug'),
            ],
            'servidor' => [
                'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido',
                'protocolo' => $_SERVER['SERVER_PROTOCOL'] ?? 'Desconhecido',
                'nome' => $_SERVER['SERVER_NAME'] ?? 'Desconhecido',
                'porta' => $_SERVER['SERVER_PORT'] ?? 'Desconhecido',
            ],
            'banco' => [
                'versao' => $this->getDatabaseVersion(),
                'tamanho' => $this->getDatabaseSize(),
                'tabelas' => $this->getDatabaseTables(),
            ],
        ];

        return view('configuracoes.sistema', compact('informacoes'));
    }

    private function getDatabaseVersion()
    {
        try {
            $results = \DB::select('SELECT VERSION() as version');
            return $results[0]->version ?? 'Desconhecido';
        } catch (\Exception $e) {
            return 'Desconhecido';
        }
    }

    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $sql = "SELECT 
                        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
                    FROM information_schema.TABLES 
                    WHERE table_schema = ?";
            
            $results = \DB::select($sql, [$database]);
            return $results[0]->size_mb . ' MB';
        } catch (\Exception $e) {
            return 'Desconhecido';
        }
    }

    private function getDatabaseTables()
    {
        try {
            $database = config('database.connections.mysql.database');
            $sql = "SELECT 
                        TABLE_NAME as name,
                        TABLE_ROWS as rows,
                        ROUND((data_length + index_length) / 1024 / 1024, 2) as size_mb
                    FROM information_schema.TABLES 
                    WHERE table_schema = ?
                    ORDER BY size_mb DESC";
            
            return \DB::select($sql, [$database]);
        } catch (\Exception $e) {
            return [];
        }
    }
}