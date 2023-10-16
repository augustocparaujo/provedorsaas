<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>cadastro mk-gestor</title>
</head>

<body>
    <br>
    <div class="box">
        <div class="img-box">
            <img src="img-formulario.png">
        </div>
        <div class="form-box">
            <h2>Criar Conta</h2>
            <p> Já é um membro? <a href="https://painel.mkgestor.com.br/"> Login </a> </p>
            <form method="post" id="form" autocomplete="off">
                <div class="input-group">
                    <label for="nome"> Nome e sobrenome</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite o seu nome e sobrenome" required>
                </div>
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Digite o seu email" required>
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="Digite o seu telefone" min="11" required>
                </div>

                <div class="input-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" min="6" required>
                </div>

                <div class="input-group">
                    <label for="cpf"> CPF ou CNPJ (será seu login)</label>
                    <input type="text" id="cpf" name="cpf" placeholder="Digite o seu  cpf ou cnpj" min="11" max="14" required>
                </div>

                <div class="input-group bg-primary">
                    <button type="submit" id="aguarde">Cadastrar</button>
                </div>
                <div id="retorno"></div>
            </form>
        </div>
    </div>
    <!-- jQuery 3 -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <script>
        $('#form').submit(function() {
            $('#aguarde').show().attr('disabled', true).text('Aguarde, Processando...');
            $.ajax({
                type: 'POST',
                url: 'cad-sys.php',
                data: $('#form').serialize(),
                success: function(data) {
                    $('#retorno').show().fadeOut(4000).html(data);
                    $('#aguarde').show().attr('disabled', false).text('Cadastrar');
                }
            });
            return false;
        });
        document.onmousedown = disableclick;

        function disableclick(event) {
            if (event.button == 2) {
                return false;
            }
        }
    </script>
</body>

</html>