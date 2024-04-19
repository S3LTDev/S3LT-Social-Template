<?php
	include 'connection2.php';
	$query = "SELECT * FROM chat ORDER BY id DESC";
	$run = $con -> query(image.png$query);
	while ($row = $run->fetch_array()) :
?>
            <div class="activity card inline" style="--delay: .2s">
                <img src="<?php echo $row['avatar'];?>?size=128" alt="">
                <div class="content">
                    <div class="title"> <?php echo $row['title'];?> <span class="handle"> <?php echo $row['name'];?> </span></div>
                    <div class="subtitle"> <?php echo $row['message'];?> </div>
                </div>
            </div>

<?php endwhile; ?>