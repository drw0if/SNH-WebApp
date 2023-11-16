<?php
    require_once '../../lib/utils.php';

    function bookshelfGet(){
        $user = getLoggedUser();

        require_once '../../lib/DB.php';

        // fetch all books
        $db = DB::getInstance();
        $query = <<<QUERY
            SELECT DISTINCT
             `b`.`id`, `b`.`name`, `b`.`picture`
            FROM `order_book` `ob`
            INNER JOIN `book` `b` ON `ob`.`book_id` = `b`.`id`
            INNER JOIN `order` `o` ON `o`.`id` = `ob`.`order_id`
            WHERE `o`.`user_id` = :user_id
        QUERY;
        $ans = $db->exec($query, [
            'user_id' => $user['id']
        ]);

        exitWithJson($ans);
    }

    if(isGet()){
        bookshelfGet();
        raiseOK();
    }

    raiseMethodNotAllowed();    
?>