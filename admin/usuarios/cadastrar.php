<?php
/*
|--------------------------------------------------------------------------
| Página: cadastrar.php
|--------------------------------------------------------------------------
| Objetivo:
| Exibir o formulário de cadastro de usuários.
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_admin.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>

</head>


<body>


<h1>Cadastrar Usuário</h1>



<form action="../../actions/usuarios/cadastrar.php" method="POST">


    <label>
        Nome:
    </label>

    <br>

    <input 
        type="text" 
        name="nome"
        required
    >


    <br><br>



    <label>
        Email:
    </label>

    <br>

    <input 
        type="email" 
        name="email"
        required
    >


    <br><br>



    <label>
        Senha:
    </label>

    <br>

    <input 
        type="password" 
        name="senha"
        required
    >


    <br><br>



    <label>
        Perfil:
    </label>

    <br>


    <select name="perfil" required>


        <option value="">
            Selecione
        </option>


        <option value="Administrador">
            Administrador
        </option>


        <option value="Recepcionista">
            Recepcionista
        </option>


        <option value="Medico">
            Médico
        </option>


    </select>



    <br><br>



    <button type="submit">

        Cadastrar

    </button>



</form>


</body>

</html>