<?php
/**
 * Created by PhpStorm.
 * User: Bruger
 * Date: 10-Jun-18
 * Time: 11:49
 */

use App\Http\Controllers;

class ConversationBuilder
{
    public function getData()
    {
        $ctr = new Controllers\MAndBController();
        $result = $ctr->getMessagesData();
        return $result;
    }

    public function submitMessage()
    {
        // TODO call ORM
    }

    public function submitButton()
    {
        // TODO call ORM
    }

    public function getAllData() {
        $ctr = new Controllers\MAndBController();
        $result = $ctr->getMessagesAllData();
        return $result;
    }

    public function getButtonData(String $mid) {
        $ctr = new Controllers\MAndBController();
        $result = $ctr->getButtonData($mid);
        return $result;
    }

    public function deleteButton()
    {

    }

    public function deleteMessage()
    {

    }
}
$CB = new ConversationBuilder();
$MessageIDs = $CB->getData();
$AllMessages = $CB->getAllData();
if (isset($_POST["submit"])){
    $QuestionText = $_POST['QuestionText'];
    $Delay = $_POST['Delay'];
    if (!$_POST['QuestionText']){
        $errorText = "Enter a text!";
    }
    if ($_POST['Delay']){
        $errorDelay = "Enter a delay-time!";
    }
    if (!$errorDelay && !$errorText){
        $resultSubmit =  '<div class="alert alert-success">Thank You! I will be in touch</div>';
    } else {
        $resultSubmit = '<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
    }
}

?>

<html lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>

<div class="alert alert-primary" role="alert">
    We are still in beta! Working on it ...
</div>

<div class="alert-div" style="width: 250px; position: fixed; height: 50px; left: 250px; top: 70px;">

    <form role="form" >
        <!--
        <div class="alert alert-danger" role="alert">
            Message IDs 5, 6, 7, 8 9 and 10 are reserved
        </div>
        -->
        <div class="form-group">
            <label for="QuestionText">Question Text</label>
            <input type="text" class="form-control" id="QuestionText" aria-describedby="emailHelp" placeholder="Question / message...">
            <?php //echo "<p class='text-danger'>$errorText</p>";?>
        </div>
        <div class="form-group">
            <label for="Delay">Delay</label>
            <input type="text" class="form-control" id="Delay" aria-describedby="emailHelp" placeholder="Seconds before displaying...">
            <?php //echo "<p class='text-danger'>$errorDelay</p>";?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="alert-div2" style="width: 250px; position: fixed; height: 50px; left: 650px; top: 70px;">
        <form onsubmit="submitMessagejs()"> // TODO fix here
            <div class="form-group">
                <label for="ButtonID">Button ID</label>
                <input type="text" class="form-control" id="ButtonID" aria-describedby="emailHelp" placeholder="ID...">
            </div>
            <div class="form-group">
                <label for="ButtonText">Button Name</label>
                <input type="text" class="form-control" id="ButtonText" aria-describedby="emailHelp" placeholder="Name/Answer...">
            </div>
            <div class="form-group">
                <label for="ButtonValue">Button Value</label>
                <input type="text" class="form-control" id="ButtonValue" aria-describedby="emailHelp" placeholder="Value...">
            </div>
            <div class="form-group">
                <label for="NextMessageID">Next Message ID</label>
                <input type="text" class="form-control" id="NextMessageID" aria-describedby="emailHelp" placeholder="ID of next message...">
                <small id="NextMessageIDhelpInline" class="text-muted">
                    Pressing this button will take the user to the message with the ID specified here.
                </small>
            </div>
            <div class="form-group">
                <label for="MID">Select Message</label>
                <select class="form-control" id="MID">
                    <?php foreach ($MessageIDs as $message): ?>
                    <option> <?php echo $message; ?> </option>
                    <?php endforeach; ?>
                </select>
                <small id="MessageIDhelpInline" class="text-muted">
                    The ID of the message, which displays this button. In other words; where is it attached.
                </small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="MessagesList" style="width: 700px; position: fixed; height: 800px; left: 1100px; top: 70px; overflow-y: scroll">
        <?php foreach ($AllMessages as $message): ?>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"> Message ID: <?php echo $message['id']; ?> </h6>
                <?php echo $message['message']; ?> <button style="float: right" type="button" class="btn btn-danger">Delete</button><button style="float: right" type="button" class="btn btn-info">Edit</button>
            </div>
            <ul class="list-group list-group-flush">
                <?php $ctr = new ConversationBuilder();
                $attachedButtons = $ctr->getButtonData($message['id']);
                foreach ($attachedButtons as $button): ?>
                <li class="list-group-item"> Button ID: <b><?php echo $button['id']; ?></b>, Button text: <b><?php echo $button['name']; ?></b>, next message ID: <b><?php echo $button['next_message_id']; ?></b><button style="float: right" type="button" class="btn btn-danger">Delete</button><button style="float: right" type="button" class="btn btn-info">Edit</button></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("form").submit(function () {
            alert("submitted");
        })
    });
</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>

</html>
