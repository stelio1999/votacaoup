<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SetupVotingSystem extends Command
{
    protected $signature = 'votacao:setup';
    protected $description = 'Configurar o sistema de votação completo';

    public function handle()
    {
        $this->info('=== Configuração do Sistema de Votação Eletrónica ===');
        
        // 1. Executar migrações
        $this->info('1. Executando migrações...');
        Artisan::call('migrate:fresh');
        $this->info('✓ Migrações executadas com sucesso.');
        
        // 2. Inserir dados iniciais
        $this->info('2. Inserindo dados iniciais...');
        $this->seedInitialData();
        $this->info('✓ Dados iniciais inseridos.');
        
        // 3. Criar usuário administrador
        $this->info('3. Criando usuário administrador...');
        $this->createAdminUser();
        $this->info('✓ Usuário administrador criado.');
        
        // 4. Configurar storage
        $this->info('4. Configurando storage...');
        Artisan::call('storage:link');
        $this->info('✓ Storage configurado.');
        
        // 5. Limpar cache
        $this->info('5. Limpando cache...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $this->info('✓ Cache limpo.');
        
        $this->info('==================================================');
        $this->info('✅ Sistema configurado com sucesso!');
        $this->info('');
        $this->info('Credenciais de acesso:');
        $this->info('📧 Email: admin@up.ac.mz');
        $this->info('🔑 Senha: admin123');
        $this->info('');
        $this->info('Para iniciar o servidor:');
        $this->info('  php artisan serve');
        $this->info('');
        $this->info('Para executar o scheduler (em outro terminal):');
        $this->info('  php artisan schedule:work');
        $this->info('==================================================');
        
        return 0;
    }
    
    private function seedInitialData()
    {
        // Categorias já estão sendo inseridas na migração
        
        // Cargos padrão
        $cargos = [
            [
                'nome' => 'Representante dos Estudantes',
                'descricao' => 'Representa os estudantes nos órgãos colegiais',
                'categoria' => 'estudante',
                'mandato_meses' => 12,
                'responsabilidades' => 'Participar das reuniões do conselho, levar demandas dos estudantes, representar os interesses estudantis.',
                'requisitos' => 'Ser estudante regularmente matriculado, ter bom histórico acadêmico, não ter pendências disciplinares.',
                'ativo' => true,
                'ordem' => 1,
            ],
            [
                'nome' => 'Coordenador de Curso',
                'descricao' => 'Coordena as atividades acadêmicas do curso',
                'categoria' => 'docente',
                'mandato_meses' => 24,
                'responsabilidades' => 'Coordenação pedagógica, planejamento das atividades, acompanhamento do corpo docente.',
                'requisitos' => 'Ser docente da instituição, ter experiência na área, disponibilidade para reuniões.',
                'ativo' => true,
                'ordem' => 2,
            ],
            [
                'nome' => 'Representante do Corpo Técnico',
                'descricao' => 'Representa os técnicos administrativos',
                'categoria' => 'tecnico_administrativo',
                'mandato_meses' => 24,
                'responsabilidades' => 'Representar os interesses dos técnicos, participar de comissões, propor melhorias.',
                'requisitos' => 'Ser funcionário técnico-administrativo, ter tempo de serviço mínimo.',
                'ativo' => true,
                'ordem' => 3,
            ],
        ];
        
        foreach ($cargos as $cargo) {
            DB::table('cargos')->insert($cargo);
        }
    }
    
    private function createAdminUser()
    {
        DB::table('users')->insert([
            'name' => 'Administrador do Sistema',
            'email' => 'admin@up.ac.mz',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'categoria' => 'tecnico_administrativo',
            'matricula' => 'ADM001',
            'departamento' => 'Tecnologias de Informação',
            'telefone' => '+258 84 123 4567',
            'ativo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Criar usuário da comissão eleitoral
        DB::table('users')->insert([
            'name' => 'Comissão Eleitoral Central',
            'email' => 'comissao@up.ac.mz',
            'password' => Hash::make('comissao123'),
            'role' => 'comissao',
            'categoria' => 'docente',
            'matricula' => 'CEC001',
            'departamento' => 'Comissão Eleitoral',
            'ativo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Criar usuário eleitor de exemplo
        DB::table('users')->insert([
            'name' => 'Maria João Macuácua',
            'email' => 'maria@up.ac.mz',
            'password' => Hash::make('maria123'),
            'role' => 'eleitor',
            'categoria' => 'estudante',
            'matricula' => 'EST2023001',
            'curso' => 'Licenciatura em Informática',
            'departamento' => 'Departamento de Informática',
            'ativo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Criar usuário docente de exemplo
        DB::table('users')->insert([
            'name' => 'Dr. João Paulo Muianga',
            'email' => 'joao@up.ac.mz',
            'password' => Hash::make('joao123'),
            'role' => 'eleitor',
            'categoria' => 'docente',
            'matricula' => 'DOC2023001',
            'curso' => 'Engenharia de Software',
            'departamento' => 'Departamento de Informática',
            'ativo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}