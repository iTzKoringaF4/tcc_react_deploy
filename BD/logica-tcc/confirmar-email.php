<?php

session_start();
ob_start();

include_once "conexao.php";

$tokenConfirmacao = htmlspecialchars(strip_tags(trim($_GET["tokenConfirmacao"])));

if (!empty($tokenConfirmacao)) {
    //echo "Chave: $tokenConfirmacao <br>";

    $query_usuario = "SELECT id FROM user WHERE token=:token LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario);
    $result_usuario->bindParam(':token', $tokenConfirmacao, PDO::PARAM_STR);
    $result_usuario->execute();

    if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        extract($row_usuario);

        $query_up_usuario = "UPDATE user SET sit_user_id = 1, token=:token WHERE id=$id";
        $up_usuario = $conn->prepare($query_up_usuario);
        $up_usuario->bindParam(':token', $tokenConfirmacao, PDO::PARAM_STR);

        if ($up_usuario->execute()) {
            $_SESSION['mensagem'] = "<div>E-mail confirmado.</div>";
            header("Location: https://main--acpetshelper.netlify.app/login");
            exit;
        } else {
            $_SESSION['mensagem'] = "<div>Erro: E-mail não confirmado.</div>";
            header("Location: https://main--acpetshelper.netlify.app/cadastrar");
            exit;
        }
    } else {
        $_SESSION['mensagem'] = "<div>Erro: Endereço inválido.</div>";
        header("Location: https://main--acpetshelper.netlify.app/cadastrar");
        exit;
    }
} else {
    $_SESSION['mensagem'] = "<div>Erro: Endereço inválido.</div>";
    header("Location: https://main--acpetshelper.netlify.app/cadastrar");
}
