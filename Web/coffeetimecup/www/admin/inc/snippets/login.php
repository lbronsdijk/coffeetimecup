<style>
    body{
        background: url("stylesheet/images/coffee_shop_bg.jpg") no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
<div class="login_box">
    <form action="" method="post">
        Email<br>
        <input type="email" name="email" /><br>
        Password<br>
        <input type="password" name="password" /><br>
        <input type="submit" value="Login" name="login" />
    </form>
</div>

<div class="register_box">
    <b>Register new account</b>
    <form action="" method="post">
        Email: <input type="email" name="register_email" /><br>
        Password: <input type="password" name="register_password" /><br>
        <br>
        Firstname: <input type="text" name="register_firstname" /><br>
        Lastname: <input type="text" name="register_lastname" /><br>
        <input type="submit" value="Register" name="register" />
    </form>
</div>