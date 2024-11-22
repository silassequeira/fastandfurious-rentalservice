<!DOCTYPE html>
<html lang="pt">
  <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login User</title>
     <link rel="stylesheet" href="style.css">
  </head>
  <body>
     <header>
         <a href="index.html" class="logo">Fast & Furious Cars Inc.</a>
     </header>
     <main>
         <div class="container">
             <form method="POST" action="loginUser.php">
                 <h2>Login de conta Pessoal</h2>
                 <div class="inputContainer">
                     <label for="username">Username ou E-mail</label>
                     <input type="text" id="username" name="username" required>
                 </div>
                 <div class="inputContainer">
                     <label for="password">Password</label>
                     <input type="password" id="password" name="password" required>
                 </div>
                 <div class="buttonContainer">
                     <a href="registerUser.html">Criar Conta</a>
                     <input type="submit" name="submitLogin" value="logIn" disabled id="submitLogin">Iniciar Sessão</button>
                 </div>
             </form>
         </div>
     </main>
  </body>
</html>