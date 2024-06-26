<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{
header('location:index.php');
}
else{
if(isset($_GET['del']) && isset($_GET['name']))
{
$id=$_GET['del'];
$name=$_GET['name'];
$sql = "delete from users WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();

$msg="Data Deleted successfully";
header('location: manage-user.php');
}
if(isset($_REQUEST['unconfirm']))
{
$aeid=intval($_GET['unconfirm']);
$memstatus=1;
$sql = "UPDATE users SET status=:status WHERE  id=:aeid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$memstatus, PDO::PARAM_STR);
$query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
$query -> execute();
$msg="Changes Sucessfully";
}
if(isset($_REQUEST['confirm']))
{
$aeid=intval($_GET['confirm']);
$memstatus=0;
$sql = "UPDATE users SET status=:status WHERE  id=:aeid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$memstatus, PDO::PARAM_STR);
$query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
$query -> execute();
$msg="Changes Sucessfully";
}
?>
<!doctype html>
<html lang="en" class="no-js">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <link rel="icon" href="img/logo.png" type="image/gif" sizes="16x16">
    <title>Manage Prescriptions
    </title>
    <!-- Font awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Sandstone Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap Datatables -->
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <!-- Bootstrap social button library -->
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <!-- Bootstrap select -->
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <!-- Bootstrap file input -->
    <link rel="stylesheet" href="css/fileinput.min.css">
    <!-- Awesome Bootstrap checkbox -->
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <!-- Admin Stye -->
    <link rel="stylesheet" href="css/style.css">
    <style>
      .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #dd3d36;
        color:#fff;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
      }
      .succWrap{
        padding: 10px;
        margin: 0 0 20px 0;
        background: #5cb85c;
        color:#fff;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
      }
    </style>
  </head>
  <body>
    <?php include('includes/header.php');?>
    <div class="ts-main-content">
      <?php include('includes/leftbar.php');?>
      <div class="content-wrapper">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <h2 class="page-title">Manage Customer
              </h2>
              <!-- Zero Configuration Table -->
              <div class="panel panel-default">
                <div class="panel-heading">List Customer
                </div>
                <div class="panel-body">
                  <?php if($error){?>
                  <div class="errorWrap" id="msgshow">
                    <?php echo htmlentities($error); ?>
                  </div>
                  <?php }
                  else if($msg){?>
                  <div class="succWrap" id="msgshow">
                    <?php echo htmlentities($msg); ?>
                  </div>
                  <?php }?>
                  <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>#
                        </th>
                        <th>Upload Date/Time
                        </th>
                        <th>Title
                        </th>
                        <th>Name
                        </th>
                        <th>Mobile Number
                        </th>
                        <th>Email
                        </th>
                        <th>Remark
                        </th>
                        <th>Prescription/Report
                        </th>
                        <!-- <th>Action
                        </th> -->

                      </tr>
                    </thead>
                    <tbody>
                      <?php $sql = "SELECT * from  prescription  where user_id=".$_GET['user']." ORDER BY id DESC";
                          $query = $dbh -> prepare($sql);
                          $query->execute();
                          $results=$query->fetchAll(PDO::FETCH_OBJ);
                          $cnt=1;
                          if($query->rowCount() > 0)
                          {
                          foreach($results as $result)
                          {				?>
                      <tr>
                        <td>
                          <?php echo htmlentities($cnt);?>
                        </td>
                        <td>
                          <?php echo htmlentities($result->created_date);?>
                        </td>
                        <td>
                          <?php echo htmlentities($result->title);?>
                        </td>
                        <td>
                          <?php echo htmlentities($result->name);?>
                        </td>
                        <td>
                          <?php echo htmlentities($result->mobile);?>
                        </td>
                        <td>
                          <?php echo htmlentities($result->email);?>
                        </td>
                        <td>
                        <?php echo htmlentities($result->remark);?>
                        </td>
                        <td>
                        <a href="javascript: void(0)" data-toggle="modal" data-target="#viewModal" data-image="<?php echo $result->image; ?>">
                          <img height="150px" src="../<?php echo htmlentities($result->image);?>" />
                        </a>
                        </td>
                        <!-- <td>
                         
                          <?php echo "<a href='manage-user.php?del=". $result->id ."&name=".$result->fname."' title='Delete Record' data-toggle='tooltip'  onClick=\"javascript:return confirm('are you sure you want to delete this?');\"><span class='glyphicon glyphicon-trash'></span></a>"; ?>
                        </td> -->

                      </tr>
                      <?php $cnt=$cnt+1; }} ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="height: 100%;">
          <div class="modal-content">
            
            <div class="modal-body">
               <image id="prevImage" style="width:100%; height:auto;"></image>
            </div>
          </div>
        </div>
      </div>



    </div>
    <!-- Loading Scripts -->
    <script src="js/jquery.min.js">
    </script>
    <script src="js/bootstrap-select.min.js">
    </script>
    <script src="js/bootstrap.min.js">
    </script>
    <script src="js/jquery.dataTables.min.js">
    </script>
    <script src="js/dataTables.bootstrap.min.js">
    </script>
    <script src="js/Chart.min.js">
    </script>
    <script src="js/fileinput.js">
    </script>
    <script src="js/chartData.js">
    </script>
    <script src="js/main.js">
    </script>
    <script type="text/javascript">
      $(document).ready(function () {
        setTimeout(function() {
          $('.succWrap').slideUp("slow");
        }
                   , 3000);
      }
                       );
    </script>

<script>

$('#viewModal').on('show.bs.modal', function(e) {
   var button =  $(e.relatedTarget);

    var image = button.data('image');
     var modal = $(this)
     $('#prevImage').attr("src","../"+image);
});

</script>
  </body>
</html>
<?php } ?>
