<?php
    require_once '../../lib/utils.php';

    function fetchOrder($user_id, $order_id){
        require_once '../../lib/DB.php';
        $db = DB::getInstance();

        $ans = $db->exec('SELECT * FROM `order` WHERE `id` = :id AND `user_id` = :user_id', [
            'id' => $order_id,
            'user_id' => $user_id,
        ]);

        if(count($ans) === 0){
            return null;
        }

        $order = $ans[0];

        $query = <<<QUERY
            SELECT `b`.`id`, `b`.`name`, `b`.`author`, `b`.`genre`, `b`.`picture`, `b`.`description`, `ob`.`quantity`
            FROM `order_book` `ob`
            INNER JOIN `book` `b` ON `ob`.`book_id` = `b`.`id`
            WHERE `ob`.`order_id` = :order_id
        QUERY;

        $ans = $db->exec($query, [
            'order_id' => $order_id
        ]);

        $items = [];

        foreach($ans as $item){
            $items[] = [
                'book_id' => $item['id'],
                'name' => $item['name'],
                'author' => $item['author'],
                'genre' => $item['genre'],
                'picture' => $item['picture'],
                'description' => $item['description'],
                'quantity' => $item['quantity']
            ];
        }

        return [
            "order_id" => $order_id,
            "total" => $order['total'],
            "items" => $items
        ];
    }

    function orderGet(){
        $user = getLoggedUser();
        require_once '../../lib/DB.php';

        if(isset($_GET["order_id"])){
            $order_id = $_GET["order_id"];
            if(!is_numeric($order_id)){
                raiseBadRequest();
            }

            $order = fetchOrder($user['id'], $order_id);

            if($order === null){
                raiseNotFound();
            }

            exitWithJson($order);
        }

        // fetch last 10 orders of user
        $db = DB::getInstance();
        $ans = $db->exec('SELECT * FROM `order` WHERE `user_id` = :user_id LIMIT 10', [
            'user_id' => $user['id']
        ]);

        if(!is_array($ans)){
            $ans = [];
        }

        exitWithJson([
            'orders' => $ans
        ]);
    }

    function orderPost(){
        $user = getLoggedUser();
        $data = getJsonPost();
    
        if(!isset($data['cart']) || !isset($data['credit_card_number']) || !isset($data['credit_card_expiration_date']) || !isset($data['credit_card_cvv'])){
            raiseBadRequest();
        }
    
        $cart = $data['cart'];
        $credit_card_number = $data['credit_card_number'];
        $credit_card_expiration_date = $data['credit_card_expiration_date'];
        $credit_card_cvv = $data['credit_card_cvv'];
    
        // check types
        if(!checkCreditCardNumber($credit_card_number) || !checkCreditCardExpirationDate($credit_card_expiration_date) || !checkCreditCardCVV($credit_card_cvv) || !checkCart($cart)){
            raiseBadRequest();
        }
    
        require_once '../../lib/DB.php';
        $db = DB::getInstance();
    
        // check if user has enough money
        $total = 0;
        foreach($cart as $item){
            $ans = $db->exec('SELECT * FROM `book` WHERE `id` = :id', [
                'id' => $item['book_id']
            ]);
    
            if(count($ans) === 0){
                exitWithJson([
                    'error' => 'Invalid book in cart!'
                ], BAD_REQUEST);
            }
    
            $book = $ans[0];
    
            $total += $book['price'] * $item['quantity'];
        }
    
        if(!performPayment($total, $credit_card_number, $credit_card_expiration_date, $credit_card_cvv)){
            exitWithJson([
                'error' => 'Payment failed!'
            ], BAD_REQUEST);
        }
    
        // add order to db
        $ans = $db->exec('INSERT INTO `order` (`user_id`, `total`) VALUES (:user_id, :total)', [
            'user_id' => $user['id'],
            'total' => $total
        ]);
    
        $order_id = $db->lastInsertId();
    
        // add books to order
        foreach($cart as $item){
            $ans = $db->exec('INSERT INTO `order_book` (`order_id`, `book_id`, `quantity`) VALUES (:order_id, :book_id, :quantity)', [
                'order_id' => $order_id,
                'book_id' => $item['book_id'],
                'quantity' => $item['quantity']
            ]);
        }
    
        raiseOK();
    }

    if(isPost()){
        orderPost();
        raiseOK();
    }

    if(isGet()){
        orderGet();
        raiseOK();
    }

    raiseMethodNotAllowed();    
?>