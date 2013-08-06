<?php include APP_VIEWS_PATH . 'header.php'; ?>

<form class="form-horizontal" method="POST" action="login.php">
  <div class="form-group">
    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
    <div class="col-lg-4">
   		<input type="text" class="form-control" id="inputEmail" placeholder="Email">
  	</div>
  
  </div>
  <div class="form-group">
    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
    <div class="col-lg-4">
      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input type="checkbox"> Remember me
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
</form>



<?php

    # Ist die $_POST Variable submit nicht leer ???
    # dann wurden Logindaten eingegeben, die müssen wir überprüfen !
    if (!empty($_POST["submit"]))
        {
        # Die Werte die im Loginformular eingegeben wurden "escapen",
        # damit keine Hackangriffe über den Login erfolgen können !
        # Mysql_real... ist auf jedenfall dem Befehle addslashes()
        # vorzuziehen !!! Ohne sind mysql injections möglich !!!!
        $_username = mysql_real_escape_string($_POST["username"]);
        $_passwort = mysql_real_escape_string($_POST["passwort"]);

        # Befehl für die MySQL Datenbank
        # Wichtig ist, die Variablen von $_username und $_passwort
        # zu umklammern. Da wir mit Anführungszeichen den SQL String
        # angeben, nehmen wir dafür die einfachen Anführungszeichen
        # die man neben der Enter Taste auf der # findet !
        $_sql = "SELECT * FROM login_usernamen WHERE
                    username='$_username' AND
                    passwort='$_passwort' AND
                    user_geloescht=0
                LIMIT 1";

        # Prüfen, ob der User in der Datenbank existiert !
        $_res = mysql_query($_sql, $link);
        $_anzahl = @mysql_num_rows($_res);

        # Die Anzahl der gefundenen Einträge überprüfen. Maximal
        # wird 1 Eintrag rausgefiltert (LIMIT 1). Wenn 0 Einträge
        # gefunden wurden, dann gibt es keinen Usereintrag, der
        # gültig ist. Keinen wo der Username und das Passwort stimmt
        # und user_geloescht auch gleich 0 ist !
        if ($_anzahl > 0)
            {
            echo "Der Login war erfolgreich.<br>";

            # In der Session merken, dass der User eingeloggt ist !
            $_SESSION["login"] = 1;

            # Den Eintrag vom User in der Session speichern !
            $_SESSION["user"] = mysql_fetch_array($_res, MYSQL_ASSOC);

            # Das Einlogdatum in der Tabelle setzen !
            $_sql = "UPDATE login_usernamen SET letzter_login=NOW()
                     WHERE id=".$_SESSION["user"]["id"];
            mysql_query($_sql);
            }
        else
            {
            echo "Die Logindaten sind nicht korrekt.<br>";
            }
        }

    # Ist der User eingeloggt ???
    if ($_SESSION["login"] == 0)
        {
        # ist nicht eingeloggt, also Formular anzeigen, die Datenbank
        # schliessen und das Programm beenden
       # include("login-formular.html");
        #mysql_close($link);
        exit;
        }

    # Hier wäre der User jetzt gültig angemeldet ! Hier kann
    # Programmcode stehen, den nur eingeloggte User sehen sollen !!
?>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>


