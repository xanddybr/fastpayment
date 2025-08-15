Users Storys

- Como admin eu quero poder logar no sistema e ter acesso a um dashboard.
- Como admin eu quero poder cadastrar, atualizar, deletar e listar os eventos cadastrados.
- Como admin eu quero poder cadastrar, atualizar, deletar e listar tipos de usuarios.
- Como admin eu quero poder cadastrar, atualizar, deletar e listar tipos de eventos.
- Como admin eu quero poder cadastrar um evento que tenha as seguintes informaçoes: evento, tipo do evento, data, horario, vaga, unidade, valor.
- Como cliente eu irei selecionar um unico evento na agenda que estará fora da area privada do admin em forma de tabela e para efetuar o pagamento do mesmo.
- Como cliente logo em seguida eu irei informar meu nome e sobrenome, email, e receberei um código de 6 digitos para validar o mesmo que me levará para o checkout pro Mercado pago para efetuar o pagamento da forma que eu desejar.
- Como aplicação após o sucesso na compra eu irei notificar o cliente e o admin enviando o comprovante de pagamento via webhook e decrementar o campo vaga na tabela agenda.
- Como aplicação eu armazenar o histórico desta compra com seus respectivos campos e nome e sobrenome do usuario.
- Como aplicação eu solicitarei para o cliente o preenchimento de informaçoes pessoas que serão finculadas a data de compra do referido evento e enviada por email para o admin.


Diagrama ASCII 


                              [Admin]
                                 |
                                 v
                        [Tela de Login Admin]
                                 |
                                 v
                        [Dashboard do Admin]
                                 |
        +------------------------+------------------------+
        |                        |                        |
        v                        v                        v
[Gerenciar Eventos]    [Gerenciar Tipos de Eventos]   [Ver Histórico]
        |                        |
        v                        v
[Cadastrar/Editar/Excluir]   [Cadastrar/Editar/Excluir]
       Evento                     Tipo

===============================================================================

                            [Cliente (Público)]
                                 |
                                 v
                   [Tabela Pública de Eventos Disponíveis]
                                 |
                                 v
               [Seleciona um evento e inicia reserva]
                                 |
                                 v
[Formulário: Nome, Sobrenome, Email + Validação via Código 6 dígitos]
                                 |
                                 v
            [Redirecionado para Checkout Pro (Mercado Pago)]
                                 |
                                 v
                        [Pagamento Bem-sucedido]
                                 |
                                 v
        [Webhook é chamado pela API do Mercado Pago]
                                 |
                                 v
                  [1. Atualiza Evento: -1 vaga]
                  [2. Cria Histórico da Compra]
                  - Vincula Evento ↔ Cliente
                  [3. Envia Comprovante por Email]
                        - Para Cliente ✅
                           - Para Admin ✅
                                 |
                                 v
         [Redireciona Cliente para Formulário Adicional]
                                 |
                                 v
 [Cliente preenche dados pessoais complementares (formulário extra)]
                                 |
                                 v
[Salva dados adicionais vinculados ao Cliente no Banco de Dados]
                                 |
                                 v
    [Envia todas as informações por email ao Admin]
                                 |
                                 v
                   [Fim do Fluxo da Aplicação 🎯]



Estrutura de Diretórios Atual do Projeto 



fastpayment/
│
├── api/                          # Toda a lógica do backend (PHP)
│   ├── config.php                 # Configuração do banco de dados (PDO)
│   ├── generic/
│   │   ├── create.php             # CRUD genérico - CREATE
│   │   ├── read.php               # CRUD genérico - READ
│   │   ├── update.php             # CRUD genérico - UPDATE
│   │   ├── delete.php             # CRUD genérico - DELETE
│   │
│   ├── login.php                  # Autenticação e geração do JWT
│   ├── validate.php               # Validação de token JWT
│   ├── logout.php                 # Logout (invalidate token client-side)
│   ├── jwt_utils.php              # Funções utilitárias para gerar/decodificar JWT
│   └── .htaccess                  # Reescrita de URLs (remover .php das rotas)
│
├── css/
│   ├── bootstrap.min.css          # Bootstrap local
│   └── style.css                  # Estilos personalizados
│
├── js/
│   ├── bootstrap.bundle.min.js    # Bootstrap JS local
│   ├── script.js                  # Funções JS gerais
│   ├── schedule.js                # Scripts para agendamento
│   └── auth.js                    # Scripts para login/logout e token
│
├── pages/
│   ├── login.html                 # Tela de login (Bootstrap estilizado)
│   ├── schedule.html              # Tela de agendamento com listagem
│   └── ...                        # Outras páginas futuras (dashboard, histórico, etc.)
│
├── uploads/                       # Caso precise armazenar imagens ou arquivos
│
├── .htaccess                      # Oculta extensões .html nas rotas do frontend
├── index.html                     # Página inicial
└── README.md                      # Documentação do projeto
