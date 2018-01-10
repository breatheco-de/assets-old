<!DOCTYPE html>
<html>
    <head>
        <title> </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    </head>
    <body>
    <style>
        table{
            width: 600px;
        }
    </style>
        <?php
        $fileName = 'data.json';
        $fileContent = file_get_contents($fileName);
        if(!$fileContent) throwError('Imposible to read the database file');
        $jSON = json_decode($fileContent);
        ?>
        <table class="table table-dark">
        <?php foreach($jSON as $game) { ?>
            <tr>
                <?php foreach($game as $item) { ?>
                <td><?php echo $item; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </table>
    </body>
</html>