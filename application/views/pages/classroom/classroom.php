<div class="row">
    <div class="col-lg-8 col-centered">
        <h1 class="cirms-page-header">
            <i class="fa fa-university fa-fw"></i>
                <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($controller)) ?>
        </h1>
    </div>
</div>
<div class="col-lg-8 col-centered bordered">
	<form class="horizontal-form" method="POST" id="add_new_room" role="form">
        <table class="cirms-table">
            <tr>
                <td><legend class="padding10 cirms-title">Classroom Details</legend></td>
            </tr>
            <tr>
            	<td>
            		<div class="form-group">
						<span class="col-sm-12 cirms-label">Room No.:</span>
						<div class="col-lg-12">
							<input type="text" class="form-control" name="room_no" id="room_no" placeholder="e.g. ST101">
						</div>
				    </div>
            	</td>
            </tr>
            <tr>
            	<td>
            		<div class="form-group">
						<span class="col-sm-12 cirms-label">Classroom Type:</span>
						<div class="col-lg-12">
                            <select class="form-control" name="classroom_type" id="classroom_type">
                                <option value="">Select Classroom Type</option>
                                <option value="e-room">E - Room</option>
                                <option value="laboratory">Laboratory</option>
                            </select>
						</div>
					</div>
            	</td>
            </tr>
            <tr>
            	<td>
            		<div class="form-group">
            			<div class="col-lg-12">
							<button type="submit" class="btn btn-primary pull-right">Submit</button>
				    	</div>
				    </div>
            	</td>
            </tr>
        </table>
	</form>
</div>
