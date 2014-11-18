


	<div class="col-md-9">
		<span class="btn btn-primary btn-xs btn-block">Notifications</span>
		<?php echo $notification_list; ?>
	</div>


	<div class="col-md-3" style="border-left:2px dotted gray">
		<span class="btn btn-primary  btn-xs btn-block">Friend Requests</span>
			
		<?php
			echo $friend_requests;
		 ?>
		<p class="status"></p>
	</div>


	<script>
		function friendReqHandler(action,reqid,user1){
			var status = $('p.status');
			status.html('');
			$.ajax({
				url:'phpParsers/friend_system.php',
				method: 'POST',
				data : {action:action,reqid:reqid,user1:user1},
				success: function(results){
					status.addClass('s');
					status.append(results)
					setTimeout(function(){
						window.location.reload();			
					},2000);
				}
			})
		}


	</script>



