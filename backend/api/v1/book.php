<?php
    require_once '../../lib/utils.php';

    function bookGet(){
        require_once '../../lib/DB.php';

        if(isset($_GET["book_id"])){
            $book_id = $_GET["book_id"];
            if(!is_numeric($book_id)){
                raiseBadRequest();
            }

            $db = DB::getInstance();
            $ans = $db->exec('SELECT * FROM `book` WHERE `id` = :id', [
                'id' => $book_id
            ]);

            if(count($ans) === 0){
                raiseNotFound();
            }

            exitWithJson($ans[0]);
        }

        // fetch all books
        $db = DB::getInstance();
        $ans = $db->exec('SELECT * FROM `book`');

        if(!is_array($ans)){
            $ans = [];
        }

        exitWithJson($ans);
    }

    if(isGet()){
        bookGet();
        raiseOK();
    }

    raiseMethodNotAllowed();    
?>