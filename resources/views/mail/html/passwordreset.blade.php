<!DOCTYPE html>
<html lang="ja">
<style>
    body {
        background-color: #fff;
    }

    h1 {
        font-size: 16px;
        color: #ff6666;
    }

    #button {
        width: 200px;
        text-align: center;
    }

    #button a {
        padding: 10px 20px;
        display: block;
        border: 1px solid #2a88bd;
        background-color: #FFFFFF;
        color: #2a88bd;
        text-decoration: none;
        box-shadow: 2px 2px 3px #f5deb3;
    }

    #button a:hover {
        background-color: #2a88bd;
        color: #FFFFFF;
    }
</style>

<body>
    <h1>
        パスワードリセット
    </h1>
    <p>
        以下を押下し、パスワードリセットの手続きを行ってください。
    </p>
    <a href={{ $reset_url . "/" . $email }}>こちらをクリック</a>
</body>

</html>
