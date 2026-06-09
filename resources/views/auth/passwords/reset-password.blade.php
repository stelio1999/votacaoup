<!DOCTYPE html>
<html lang="pt-MZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Palavra-passe</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1a365d 0%, #3182ce 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
            background: white;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #3182ce 0%, #1a365d 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            margin: 20px 0;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(49, 130, 206, 0.3);
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(49, 130, 206, 0.4);
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        .info-box {
            background: #ebf8ff;
            border-left: 4px solid #3182ce;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning-box {
            background: #fff5f5;
            border-left: 4px solid #e53e3e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .token-box {
            background: #1a202c;
            color: #a0aec0;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            margin: 20px 0;
            word-break: break-all;
        }
        .link-box {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            word-break: break-all;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo-up.png') }}" alt="UP Maputo">
            <h1>Redefinição de Palavra-passe</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Sistema de Votação Eletrónica</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <h2 style="color: #1a365d; margin-top: 0;">Olá, {{ $userName }}!</h2>
            
            <p style="font-size: 16px; margin-bottom: 25px;">
                Recebemos um pedido de redefinição de palavra-passe para a sua conta no 
                <strong>Sistema de Votação Eletrónica da Universidade Pedagógica de Maputo</strong>.
            </p>
            
            <!-- Botão de redefinição -->
            <div style="text-align: center;">
                <a href="{{ $resetLink }}" class="button">
                    🔐 REDEFINIR PALAVRA-PASSE
                </a>
            </div>
            
            <!-- Link alternativo -->
            <div class="link-box">
                <p style="margin: 0 0 10px; font-weight: 600; color: #276749;">
                    <span style="font-size: 18px;">🔗</span> Link directo:
                </p>
                <p style="margin: 0; font-size: 14px; word-break: break-all;">
                    <a href="{{ $resetLink }}" style="color: #3182ce; text-decoration: none;">
                        {{ $resetLink }}
                    </a>
                </p>
            </div>
            
            <!-- Informações importantes -->
            <div class="info-box">
                <p style="margin: 0; font-weight: 600; color: #2c5282;">
                    ⏰ Válido por 60 minutos
                </p>
                <p style="margin: 10px 0 0; font-size: 14px;">
                    Este link expira em <strong>60 minutos</strong> por questões de segurança.
                    Se não solicitou esta redefinição, ignore este email.
                </p>
            </div>
            
            <!-- Token para desenvolvimento -->
            @if(app()->environment('local'))
            <div class="token-box">
                <p style="margin: 0 0 10px; font-weight: 600; color: #ecc94b;">
                    🧪 AMBIENTE DE DESENVOLVIMENTO
                </p>
                <p style="margin: 0 0 5px; color: #cbd5e0;">
                    <strong>Token:</strong> {{ $token }}
                </p>
                <p style="margin: 0; color: #cbd5e0; font-size: 12px;">
                    Este token só aparece em ambiente local.
                </p>
            </div>
            @endif
            
            <!-- Aviso de segurança -->
            <div class="warning-box">
                <p style="margin: 0; font-weight: 600; color: #c53030;">
                    ⚠️ Atenção!
                </p>
                <p style="margin: 10px 0 0; font-size: 14px;">
                    Nunca compartilhe este link com ninguém. A equipa do sistema 
                    nunca solicitará a sua palavra-passe ou este link.
                </p>
            </div>
            
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 14px; color: #718096;">
                Se não foi você quem solicitou esta redefinição, recomendamos que:
            </p>
            <ol style="font-size: 14px; color: #718096; margin-top: 5px;">
                <li>Ignore este email - sua palavra-passe não será alterada</li>
                <li>Contacte o suporte técnico: <a href="mailto:suporte@up.ac.mz" style="color: #3182ce;">suporte@up.ac.mz</a></li>
                <li>Verifique a segurança da sua conta</li>
            </ol>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div style="margin-bottom: 15px;">
                <a href="#" style="color: #718096; text-decoration: none; margin: 0 10px;">
                    <img src="{{ asset('images/logo-up-small.png') }}" alt="UP" style="height: 30px;">
                </a>
            </div>
            <p style="margin: 5px 0;">
                <strong>Universidade Pedagógica de Maputo</strong>
            </p>
            <p style="margin: 5px 0;">
                Av. de Moçambique, Maputo - Moçambique
            </p>
            <p style="margin: 15px 0 0; font-size: 12px;">
                &copy; {{ date('Y') }} Sistema de Votação Eletrónica. Todos os direitos reservados.
            </p>
            <p style="margin: 10px 0 0; font-size: 11px; color: #a0aec0;">
                Este é um email automático, por favor não responda.
            </p>
        </div>
    </div>
</body>
</html>