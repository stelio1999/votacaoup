<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\EleicaoController;
use App\Http\Controllers\VotacaoController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\ForgotPasswordController;

// Rota inicial (pública)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Rotas de recuperação de senha
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Rotas alternativas com código de verificação
Route::get('password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('password/verify', [ForgotPasswordController::class, 'sendVerificationCode'])->name('password.send-code');
Route::get('password/verify-code', [ForgotPasswordController::class, 'showCodeVerificationForm'])->name('password.verify.code');
Route::post('password/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify-code');

// Rotas protegidas (requerem autenticação)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Usuários (apenas administrador)
    Route::middleware(['role:admin'])->group(function () {
Route::resource('usuarios', UserController::class)->parameters([
    'usuarios' => 'user'
]);
        Route::resource('cargos', CargoController::class);
        Route::get('candidatos/buscar-usuarios', [CandidatoController::class, 'buscarUsuarios'])->name('candidatos.buscar-usuarios');

        Route::resource('candidatos', CandidatoController::class);
        Route::resource('configuracoes', ConfiguracaoController::class);

// Toggle status do usuário
Route::patch('usuarios/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('usuarios.toggle-status');

        // Adicionar estas rotas ao grupo de administrador:

// Rotas para cargos
Route::post('cargos/{cargo}/toggle-status', [CargoController::class, 'toggleStatus'])->name('cargos.toggle-status');

// Rotas para candidatos
Route::patch('candidatos/{candidato}/aprovar', [CandidatoController::class, 'aprovar'])->name('candidatos.aprovar');
Route::patch('candidatos/{candidato}/reprovar', [CandidatoController::class, 'reprovar'])->name('candidatos.reprovar');

// Rotas para resultados
Route::get('resultados/publicos', [ResultadoController::class, 'publicos'])->name('resultados.publicos');
Route::get('resultados/{eleicao}/exportar/{formato}', [ResultadoController::class, 'exportar'])->name('resultados.exportar');

// Rotas para relatórios
Route::post('relatorios/gerar', [RelatorioController::class, 'gerar'])->name('relatorios.gerar');
Route::get('relatorios/exportar/{tipo}', [RelatorioController::class, 'exportar'])->name('relatorios.exportar');

// Rotas para configurações
Route::get('configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
Route::post('configuracoes/atualizar', [ConfiguracaoController::class, 'atualizar'])->name('configuracoes.atualizar');
Route::get('configuracoes/backup', [ConfiguracaoController::class, 'backup'])->name('configuracoes.backup');
Route::get('configuracoes/limpar-cache', [ConfiguracaoController::class, 'limparCache'])->name('configuracoes.limpar-cache');
Route::get('configuracoes/logs', [ConfiguracaoController::class, 'logs'])->name('configuracoes.logs');
Route::get('configuracoes/limpar-logs', [ConfiguracaoController::class, 'limparLogs'])->name('configuracoes.limpar-logs');
Route::get('configuracoes/sistema', [ConfiguracaoController::class, 'sistema'])->name('configuracoes.sistema');

// Rotas para votação
Route::get('votacao/comprovante/{voto}', [VotacaoController::class, 'comprovante'])->name('votacao.comprovante');
Route::get('votacao/historico', [VotacaoController::class, 'historico'])->name('votacao.historico');
    });
    
    // Votação (acesso para eleitores)
    Route::middleware(['role:eleitor'])->group(function () {
        Route::get('/votacao', [VotacaoController::class, 'index'])->name('votacao.index');
        Route::get('/votacao/{eleicao}', [VotacaoController::class, 'show'])->name('votacao.show');
        Route::post('/votacao/{eleicao}/votar', [VotacaoController::class, 'votar'])->name('votacao.votar');
    
         Route::get('votacao/comprovante/{voto}', [VotacaoController::class, 'comprovante'])
        ->name('votacao.comprovante');
    
    
        });
    
    // Resultados e relatórios (acesso para comissão eleitoral)
Route::middleware(['role:admin,comissao'])->group(function () {
        Route::get('/resultados', [ResultadoController::class, 'index'])->name('resultados.index');
        Route::get('/resultados/{eleicao}', [ResultadoController::class, 'show'])->name('resultados.show');
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::get('/relatorios/exportar/{tipo}', [RelatorioController::class, 'exportar'])->name('relatorios.exportar');

         Route::get('/relatorios', [RelatorioController::class, 'index'])
        ->name('relatorios.index');

    Route::post('/relatorios/gerar', [RelatorioController::class, 'gerar'])
        ->name('relatorios.gerar');

    Route::get('/relatorios/exportar/{tipo}', [RelatorioController::class, 'exportar'])
        ->name('relatorios.exportar');


// Rotas para candidatos
Route::patch('candidatos/{candidato}/aprovar', [CandidatoController::class, 'aprovar'])->name('candidatos.aprovar');
Route::patch('candidatos/{candidato}/reprovar', [CandidatoController::class, 'reprovar'])->name('candidatos.reprovar');
 Route::get('candidatos/buscar-usuarios', [CandidatoController::class, 'buscarUsuarios'])->name('candidatos.buscar-usuarios');

        Route::resource('candidatos', CandidatoController::class);
       


    });

    Route::prefix('eleicoes')->name('eleicoes.')->group(function () {
        Route::get('/', [EleicaoController::class, 'index'])->name('index');
        Route::get('/create', [EleicaoController::class, 'create'])->name('create');
        Route::post('/', [EleicaoController::class, 'store'])->name('store');
        Route::get('/{eleicao}', [EleicaoController::class, 'show'])->name('show');
        Route::get('/{eleicao}/edit', [EleicaoController::class, 'edit'])->name('edit');
        Route::put('/{eleicao}', [EleicaoController::class, 'update'])->name('update');
        Route::delete('/{eleicao}', [EleicaoController::class, 'destroy'])->name('destroy');

        // Rotas para iniciar e encerrar eleição
        Route::post('/{eleicao}/iniciar', [EleicaoController::class, 'iniciar'])->name('iniciar');
        Route::post('/{eleicao}/encerrar', [EleicaoController::class, 'encerrar'])->name('encerrar');

        Route::get('/{eleicao}/candidatos', [EleicaoController::class, 'candidatos'])
    ->name('candidatos');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::patch('/preferences/update', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
        Route::patch('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/activity', [ProfileController::class, 'activity'])->name('activity');
        Route::get('/export', [ProfileController::class, 'exportData'])->name('export');
        Route::delete('/delete', [ProfileController::class, 'deleteAccount'])->name('delete');
    });
});