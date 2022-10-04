<?php
require_once 'PDOConnection.php';

?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th>bill id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email </th>
                    <th>phone </th>
                    <th>address </th>
                    <th>status</th>
                    <!-- <th>edit status</th> -->
                    <th>Edit</th>
                    <th>Delete</th>
                    </tr>
                                    </thead>

                    <?php
    // $insert = $conn->prepare("SELECT * FROM orders 
    // inner join bill  
    // on orders.bill_id = bill.id");
    $insert = $conn->prepare("SELECT * FROM orders");
    $insert->execute();
    $results = $insert->fetchAll(PDO::FETCH_OBJ);
    foreach ($results as $result) {
    ?>
        <tr>
            <td><?php echo htmlentities($result->bill_id); ?></td>
            <td><?php echo htmlentities($result->f_name); ?></td>
            <td><?php echo htmlentities($result->l_name); ?></td>
            <td><?php echo htmlentities($result->email); ?></td>
            <td><?php echo htmlentities($result->phone); ?></td>
            <td><?php echo htmlentities($result->address); ?></td>
            <td><?php echo htmlentities($result->status); ?></td>
            <td><a href="updateorder.php?id=<?php echo htmlentities($result->id); ?>"class="btn btn-warning btn-circle"> <i class="far fa-edit"></i></a></td>
            <td><a href="orders.php?del=<?php echo htmlentities($result->id); ?>"class="btn btn-danger btn-circle"> <i class="fas fa-trash"></i></td>

        </tr>
    <?php } ?>
</table>
<?php
// Code for record deletion
if (isset($_REQUEST['del'])) {
    //Get row id
    $id = $_GET['del'];
    //Qyery for deletion
    $sql = "DELETE FROM orders WHERE id='$id'";
    // Prepare query for execution
    $query = $conn->prepare($sql);
    // Query Execution
    $query->execute();
    // Mesage after updation
    echo "<script>alert('Record Updated successfully');</scrip>";
    // Code for redirection
    // echo "<script>window.location.href='http://localhost/formvvvvv/login.php'</script>";
}
?>
            