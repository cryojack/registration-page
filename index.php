<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="assets/webicon.png"/>
  <link rel="stylesheet" type="text/css" href="resources/css/bootstrap.css"/>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Main Page</title>
  <style>
    .pheading1{
      text-align: center;
      margin-top: 1.5em;
    }
    .pdiv1{
      margin-left: 10em;
      margin-top: 2em;
      margin-right: 10em;
    }
  </style>
</head>
  <body>
    <h2 class="pheading1">Add Contact Details</h2>
    <div class="pdiv1">
      <form action="addcontact.php" method="post">
        <div class="form-group row">
          <label for="first-name" class="col-sm-2 col-form-label" style="font-weight:bold">First Name</label>
          <input type="text" class="form-control col-sm-7" name="first-name" placeholder="First Name">
        </div>
        <div class="form-group row">
          <label for="last-name" class="col-sm-2 col-form-label" style="font-weight:bold">Last Name</label>
          <input type="text" class="form-control col-sm-7" name="last-name" placeholder="Last Name">
        </div>
        <div class="form-group row">
          <label for="emailid" class="col-sm-2 col-form-label" style="font-weight:bold">Email</label>
          <input type="text" class="form-control col-sm-7" name="emailid" placeholder="abc@site.com">
        </div>
        <div class="form-group row">
          <label for="comments" class="col-sm-2 col-form-label" style="font-weight:bold">Comment</label>
          <textarea name="comments" class="form-control col-sm-7" style="height:100px" placeholder="Describe your experience"></textarea>
        </div>
        <div class="form-group row">
          <div class="offset-sm-2">
            <button type="submit" name="submitbtn" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
