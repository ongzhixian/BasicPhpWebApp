<!DOCTYPE html>
<html lang="en">
<head>
  <title>9034</title>
  <meta charset="utf-8" />
  <meta name="description" content="Mobile site" />
  <meta name="author" content="Ong Zhi Xian" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/skeleton.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
  <style>
body {
    color: #3B4953;
}

header.page {
    border: 2px solid #90AB8B;
    padding: 1rem 2rem;
}

header.page > h1 {
    margin: 0;
    display: inline-block;
}

nav.navbar > a {
    color: #5A7863;
    text-decoration: none;
    font-size: 1.67rem;
    margin-right: 1.67rem;
}

nav.navbar > a:hover {
    text-decoration: underline;
}

article {
    padding: 1rem 2rem;
}
  </style>
</head>
<body>

    <header class="page">
        <h1>Student Records</h1>
        <nav class="navbar">
            <a href="#link1">Link 1</a>
            <a href="#link2">Link 2</a>
            <a href="#link3">Link 3</a>
        </nav>
    </header>

    <article>

        <form>
            <div class="row">
                <div class="four columns">
                    <label for="inputUsername">Username</label>
                    <input class="u-full-width" type="text" placeholder="Account username" id="inputUsername">
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    <label for="inputPassword">Password</label>
                    <input class="u-full-width" type="password" placeholder="Account password" id="inputPassword">
                </div>
            </div>

            <div class="row">
                <input class="button-primary" type="submit" value="Log in">
            </div>

            <!--
            <div class="row">
                <div class="four columns">
                    <label for="exampleRecipientInput">Reason for contacting</label>
                    <select class="u-full-width" id="exampleRecipientInput">
                        <option value="Option 1">Questions</option>
                        <option value="Option 2">Admiration</option>
                        <option value="Option 3">Can I get your number?</option>
                    </select>
                </div>
            </div>
            -->

        </form>

    </article>

  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <div class="container" style="display: flex; flex-direction: column; justify-content: center;">

<!--
    <div class="row" style="padding: 1em; border: 0px solid #11f1f1;">
      <div>
        <h1 margin-bottom: 1em;">Site 9034 - a PHP Site</h1>

        <p>
            <?php
            echo "Hi, I'm a PHP script!";
            ?>
        </p>

      </div>
    </div>
-->

  </div>

</body>
</html>
