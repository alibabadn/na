<?php include '_header.php';

if (empty($_GET['u'])) redirect_to(App::url('account/index.php'));
$user = DB::table('activationFee')->where('id', $_GET['u'])->first();
?>
<?php if (is_null($user)) redirect_to(App::url('account/index.php')); ?>
<?php  

$Validmaching = DB::table('activationFee')->where('sender_id', $user_id)->where('id', $_GET['u'])->first(); 
if (is_null($Validmaching)) redirect_to(App::url('account/index.php'));



  $ProfileCh = DB::table('userdetails')->where('userid', $user_id)->first();
     if (is_null($ProfileCh)) redirect_to(App::url('account/account.php'));
  
  $ProfileReceiver = DB::table('userdetails')->where('userid', $Validmaching->receiver_id)->first();
  $userReceiver = DB::table('users')->where('id', $Validmaching->receiver_id)->first();
?>

 <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Activation Fees Payment - (<?php echo $settings->currency; ?> <?php echo $Validmaching->amount; ?>)  
            </h2>
            </div>
          </header>
    
    <!-- Content Header  (Page header) -->
   
    <!-- Main content -->
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">

                <?php if (isset($_POST['cannotPay'])){
                    $BlockU = DB::table('users')->where('id', $user_id)->update(array('role_id' => '3'));
                    $BlockU = DB::table('activationfee')->where('sender_id', $user_id)->update(array('expiringTime' => 'NULL'));

                    redirect_to(App::url('account/activationban.php'));                }
                ?>

               <?php if (isset($_POST['confirmPay'])){
                    $id = $_POST['id'];
                    $payment_method = $_POST['payment_method'];
                    $bankname = $_POST['bankname'];
                    $accountnumber = $_POST['accountnumber'];
                    $accountname = $_POST['accountname'];
                    $depositor = $_POST['depositor'];
                    $paymentlocal = $_POST['paymentlocal'];
                      if ($payment_method == "NULL"){
       echo '<div class="alert alert-danger" role="alert">
        Theres error with your request, Please choose your payment method from the drop-down menu
        </div>'; 
           }elseif ($bankname == "" || $accountnumber == "" || $accountname == ""  || $depositor == ""  || $paymentlocal == ""){
       echo '<div class="alert alert-danger" role="alert">
        Theres error with your request, Please fill out all the form field its required
        </div>'; 
           }
           else{



          if ($_FILES['fileToUpload']['error'] > 0) {
           echo '<div class="alert alert-danger" role="alert">
        Error: " '. $_FILES['fileToUpload']['error'] . '"<br />
            </div>';
       } else {
    // array of valid extensions
    $validExtensions = array('.jpg', '.jpeg', '.gif' ,'.png');
    // get extension of the uploaded file
    $fileExtension = strrchr($_FILES['fileToUpload']['name'], ".");
    // check if file Extension is on the list of allowed ones
    if (in_array($fileExtension, $validExtensions)) {
        // we are renaming the file so we can upload files with the same name
        // we simply put current timestamp in fron of the file name
        $newName = time() . '_' . $_FILES['fileToUpload']['name'];
        $destination = '../images/' . $newName;
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destination)) {
            $paymentpof = $newName;
        }
    } else {
       
        echo '<div class="alert alert-danger" role="alert">
  Theres error with your request, Please choose an image before submitting
</div>';
    }
}
                     confirmMyActivationFee($payment_method,$bankname,$accountnumber,$accountname,$depositor ,$paymentlocal,$paymentpof, $user_id, $id);
                }
          }
             ?>
              <div class="row">
           <div class="col-lg-6">


               <div class="card">
                    <div class="card-close">
                      <div class="dropdown">
                        <button type="button" id="closeCard1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-ellipsis-v"></i></button>
                        <div aria-labelledby="closeCard1" class="dropdown-menu dropdown-menu-right has-shadow"><a href="settings.php" class="dropdown-item edit"> <i class="fa fa-gear"></i>Account Settings</a>
                            <a href="message" class="dropdown-item edit"> <i class="fa fa-envelope"></i>Get Support</a></div>
                      </div>
                    </div>
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Receiver Profile</h3>
                    </div>
                    <div class="card-body" style="padding-left: 3px;padding-right: 0px;">
                       <center><img class="img-fluid rounded-circle" src="<?php echo $ProfileReceiver->avater; ?>" style="width: 80px;" alt="User profile picture"></center>

                        <h3 class="profile-username text-center"><?php echo $ProfileReceiver->accountname;  ?></h3>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <i class="fa fa-user"></i> <a class="pull-right"><?php echo $ProfileReceiver->accountname;  ?></a>
                            </li>
                                                            <li class="list-group-item">
                                    <i class="fa fa-phone"></i> <a class="pull-right"><?php echo $ProfileReceiver->phonenumber;  ?></a>
                                </li>
                                                        <li class="list-group-item">
                                <i class="fa fa-envelope-o"></i> <a class="pull-right"><?php echo $userReceiver->email;  ?></a>
                            </li>


                            <div class="box box-success">

                                <div class="box-header with-border">
                                                                            <h3 class="box-title">Default Bank Account</h3>
                                                                    </div>
                                <div class="box-body">
                                    
                                        <ul class="list-group list-group-unbordered">
                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Bank Name <a class="pull-right"><?php echo $ProfileReceiver->bankname;  ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Account Name <a class="pull-right"><?php echo $ProfileReceiver->accountname;  ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Account Number <a class="pull-right"><?php echo $ProfileReceiver->accountnumber;  ?></a>
                                            </li>

                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Account Type <a class="pull-right"><?php echo $ProfileReceiver->accounttype;  ?></a>
                                            </li>
                                        </ul>
                    </div>
                  </div>
                </div>
                
              
            </div>
        </ul>
    </div>


  <!-- Line Chart            -->
                <div class="chart col-lg-6 col-12">

                  <?php 

                   $userBlocked = DB::table('users')->where('id', $ProfileReceiver->userid)->first();
                   if ($userBlocked->role_id == 3) {
                    echo '
                    <div class="card-body text-center" style="background-color: #dc3545; color: #fff;"><h3>User with this account is blocked, possible time elapse or violate our terms, please contact support here incase you need help <a href="support.php">Contact Us</a> </h3></div>';
                  
                   }
                   else{

                  $CheckingMarching = DB::table('activationFee')->where('sender_id', $user_id)->where('id', $user->id)->first(); 
                        $timeCreated = $CheckingMarching->expiringTime;
                        $timeNow = date('Y-m-d H:i:s');
//                        if ($timeCreated < $timeNow){
//                            var_dump("Expired");
//                        }
                  if ($CheckingMarching->payment_status == "pending") {
                   ?>
                   <div class="card-body text-center" style="background-color: #dc3545; color: #fff;">You are to make payment to the receiver below<br>Time left to complete payment<br> <p id="demo" style="font-size: 25px;"></p></div>
                    <script>
// Set the date we're counting down to
var countDownDate = new Date("<?php echo $timeCreated; ?>").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
    document.getElementById("demo").innerHTML = days + "d |" + hours + "h | "
    + minutes + "m |" + seconds + "s ";
    
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "EXPIRED";
    }
}, 1000);
</script>
                   <div class="work-amount card">
                   
                    <div class="card-body" style="padding-left: 3px;padding-right: 0px;">

                        <?php
                        $uid = Auth::user()->id;
                        //var_dump($uid);
                        $bank_details = DB::table('userdetails')->where('userid', $uid)->first(); ?>


                        <form method="POST" action="" enctype="multipart/form-data" id="something" class="form-horizontal">
                    <h3>Enter Payment Details</h3>
                       <input name="id" type="hidden" value="<?php echo $_GET['u']; ?>">
                        <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <label class="col-sm-3 form-control-label">Payment Method</label>
                          <div class="col-sm-9">
                           <select id="payment_method" name="payment_method" required="required" class="form-control">
                               <option value="" selected="selected">Select Payment Method</option>
<!--                               <option value="Bitcoin Wallet">Bitcoin Wallet</option>-->
                               <option value="Bank Transfer">Bank Transfer</option>
                               <option value="Bank Deposit">Bank Deposit</option></select>
                          </div>
                        </div>

                      <div style="display:none;">

                         <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <label class="col-sm-3 form-control-label">Bank</label>
                          <div class="col-sm-9">
                            <input id="inputHorizontalSuccess" name="bankname" type="hidden" value="<?php echo $bank_details->bankname ?>" placeholder="Your Bank Name" class="form-control form-control-success">
                          </div>
                        </div>

                         <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <label class="col-sm-3 form-control-label">Account Number</label>
                          <div class="col-sm-9">
                            <input id="inputHorizontalSuccess" type="hidden" value="<?php echo $bank_details->accountnumber ?>" name="accountnumber" placeholder="Account Number Paid Into" class="form-control form-control-success">
                          </div>
                        </div>


                         <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <label class="col-sm-3 form-control-label">Account Name</label>
                          <div class="col-sm-9">
                            <input id="inputHorizontalSuccess" type="hidden" value="<?php echo $bank_details->accountname ?>" name="accountname" placeholder="Account Name Paid Into" class="form-control form-control-success">
                          </div>
                        </div>

                         <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <label class="col-sm-3 form-control-label">Depositor's Name</label>
                          <div class="col-sm-9">
                            <input id="inputHorizontalSuccess" name="depositor" type="hidden" value="<?php echo $bank_details->firstname." ".$bank_details->lastname ?>" placeholder="Enter the Depositor's Name" class="form-control form-control-success">
                          </div>
                        </div>

                          <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <label class="col-sm-3 form-control-label">Payment Location</label>
                          <div class="col-sm-9">
                            <input id="inputHorizontalSuccess" name="paymentlocal" type="hidden" value="<?php echo $bank_details->state ?>" placeholder="Enter the Depositor's Location" class="form-control form-control-success">
                          </div>
                        </div>

                      </div>
                          <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <label class="col-sm-3 form-control-label">Screenshot</label>
                          <div class="col-sm-9">
                           <input type="file" id="fileToUpload" name="fileToUpload" required="required">
                          </div>
                        </div>

                        <div class="form-group row" style="padding: 5px 15px;padding-left: 0px;padding-right: 0px;">
                          <div class="row">
                            <div class="col-md-3 offset-md-1">
                            <input type="submit" name="confirmPay" value="Make Payment" class="btn btn-primary btn-lg" style="margin-bottom: 5px;">
                          </div>
                           <div class="col-md-2 offset-md-4">
                            <input type="submit" onclick="confirm('Are you Sure you will not pay?)" value="I can't Pay" name="cannotPay" class="btn btn-danger btn-lg" style="margin-bottom: 5px;">
                          </div>
                        </div>
                      </form>
                      </form>
                    </div>
                  </div>
             
             
              </div>
                    <?php
                  }
                  elseif ($CheckingMarching->payment_status == "waiting") {
                    echo '  <div class="card-body text-center" style="background-color: #dc3545; color: #fff;"><h3>Your activation fees payment proof has been submit and waiting for confirmation, Please wait while we check and confirm your account</h3></div>';
                  }

                   elseif ($CheckingMarching->payment_status == "confirm") {
                    echo '  <div class="card-body text-center" style="background-color: #218838; color: #fff;"><h3>This payment has been confirmed by '.Config::get('app.name').', Happy Earning</h3></div>';
                  }else{
                     echo '  <div class="card-body text-center" style="background-color: #dc3545; color: #fff;"><h3>This payment status is not understood, please contact support for possible assistant</h3></div>';
                    }
                  }
                    ?>
            </div>
                  
          </section>


    <?php include '_footer.php'; ?>