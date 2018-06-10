<?php
/**
 * Created by PhpStorm.
 * User: Bruger
 * Date: 10-Jun-18
 * Time: 11:49
 */

class ConversationBuilder
{
    private function getData()
    {

        return 1;
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

        <form>
            <div class="alert alert-danger" role="alert">
                Message IDs 5, 6, 7, 8 9 and 10 are reserved
            </div>
            <div class="form-group">
                <label for="MessageID">Message ID</label>
                <input type="text" class="form-control" id="MessageID" aria-describedby="emailHelp" placeholder="Message ID...">
            </div>
            <div class="form-group">
                <label for="QuestionText">Question Text</label>
                <input type="text" class="form-control" id="QuestionText" aria-describedby="emailHelp" placeholder="Question / message...">
            </div>
            <div class="form-group">
                <label for="Delay">Delay</label>
                <input type="text" class="form-control" id="Delay" aria-describedby="emailHelp" placeholder="Seconds before displaying...">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div class="alert-div2" style="width: 250px; position: fixed; height: 50px; left: 650px; top: 70px;">
            <form>
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
                    <label for="exampleFormControlSelect1">Example select</label>
                    <select class="form-control" id="exampleFormControlSelect1">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                    <small id="MessageIDhelpInline" class="text-muted">
                        The ID of the message, which displays this button. In other words; where is it attached.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>















    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>

</html>
