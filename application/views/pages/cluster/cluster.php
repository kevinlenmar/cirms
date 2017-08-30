<div class="row">
    <div class="col-lg-8 col-centered">
        <h1 class="cirms-page-header">
            <i class="fa fa-sitemap fa-fw"></i>
                <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($controller)) ?>
        </h1>
    </div>
</div>
<div class="col-lg-8 col-centered bordered">
	<form class="horizontal-form" method="POST" id="add_new_department" role="form">
        <table class="cirms-table">
            <tr>
                <td><legend class="padding10 cirms-title">Cluster Details</legend></td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Room No.</span>
                        <div class="col-lg-12">
                            <input type="text" class="form-control" name="room_no" id="room_no" placeholder="e.g. ST106" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
            	<td>
            		<div class="form-group">
						<span class="col-sm-12 cirms-label">Cluster Code:</span>
						<div class="col-lg-12">
							<input type="text" class="form-control" name="cluster_code" id="cluster_code" placeholder="Input Cluster code">
						</div>
				    </div>
            	</td>
            </tr>
            <tr>
            	<td>
            		<div class="form-group">
						<span class="col-sm-12 cirms-label">Cluster Name:</span>
						<div class="col-lg-12">
							<input type="text" class="form-control" name="cluster_name" id="cluster_name" placeholder="Input Cluster Name">
						</div>
					</div>
            	</td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Cluster Type:</span>
                        <div class="col-lg-12">
                            <select class="form-control" name="type" id="type">
                                <option value="">Select Cluster Type</option>
                                <option value="department">Department</option>
                                <option value="office">Office</option>
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
