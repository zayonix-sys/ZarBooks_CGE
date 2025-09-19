<?php use Illuminate\Support\Arr;

$__env->startSection('content'); ?>

<?php $__env->startPush('head'); ?>
	<link href="<?php echo e(asset('lib/select2/select2.min.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('lib/flatpickr/flatpickr.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('footer'); ?>
	<script src="<?php echo e(asset('lib/select2/select2.min.js')); ?>"></script>
	<script src="<?php echo e(asset('lib/datatables/js/jquery.dataTables.min.js')); ?>"></script>
	<script src="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.js')); ?>"></script>
	<script src="<?php echo e(asset('lib/datatables.net-responsive-bs4/dataTables.responsive.js')); ?>"></script>
	<script src="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.js')); ?>"></script>
	<script src="<?php echo e(asset('lib/datatables/js/dataTableSum.js')); ?>"></script>
	<script src="<?php echo e(asset('lib/flatpickr/flatpickr.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/number-formatter.js')); ?>"></script>

	<script type="text/javascript">


		$(document).ready(function(){
			//Select Focus on Search Filed
			$(document).on('select2:open', () => {
			    document.querySelector('input.select2-search__field').focus();
			});

			// Select Open on Down Key
			$(document).on('keydown', '.select2', function(e) {
				if (e.originalEvent && e.which == 40)
				{
			    	e.preventDefault();
			    	$(this).siblings('select').select2('open');
			  	}
			});

			$('select').select2({
			  selectOnClose: true
			});

			//Inatialize Select Picker
			$('select').select2({
				theme: 'bootstrap4',
			});

			//Inatialize Transcation Date Picker
			$('#dpVoucherDate').flatpickr({
				dateFormat: 'd-M-Y',
				defaultDate: 'today'
			});

			//Function to Add Account Row
			function AddAccountRow()
			{
				$tableBody = $('#tblTransEntry').find("tbody"),
					$trLast = $tableBody.find("tr:last"),
					//$trNew = $trLast.clone();

					$trLast.after('<tr><td class="align-middle"><select class="form-control form-control-sm selectpicker"><option>Cash In Hand</option><option>Banks</option><option>Receivables</option><option>Paybale</option><option>General Expenses</option><option>Revenues</option></select></td><td class="align-middle"><input class="form-control form-control-sm" name="txtParticular" type="text" placeholder="Enter Particular"></td><td class="align-left"><input class="form-control form-control-sm transAmount" name="txtEntryAmnt" type="number" placeholder="Enter Amount"></td><td class="deleteRow align-middle text-center cursor-pointer"><span class="badge rounded-capsule badge-soft-danger cursor-pointer">Delete<span class="ml-1 fas fa-window-close fa-lg"></span></span></td></tr>');
			}
			//Add New Row in Entry Table
			$('#tblTransEntry').on('keyup', 'td:nth-child(3)', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13')
				{
					AddAccountRow();
				}
				$('select').select2({
						theme: 'bootstrap4',
				});
				calculateRowSum();
			});

			$('#btnAddAccount').on('click', function(){
				AddAccountRow();
			});

			//Remove Row in Entry Table
			$('#tblTransEntry').on('click', '.deleteRow', function(){
				$rowCount = $('#tblTransEntry tbody tr').length;
				if($rowCount > 1)
				{
					$(this).closest('tr').remove();
				}
				calculateRowSum();
			});

			//Display Total Sum of Accounts
			function calculateRowSum()
			{
			   	var sum = 0;
			    $('.transAmount').each(function(){
				    sum += parseFloat($(this).val() || 0);
			    	$('#txtTrnsTotal').text(sum).formatNumber();
				});
			}

			//Autocomplete Accounts
		});

	</script>


<?php $__env->stopPush(); ?>

<div class="row">
	<div class="col-md-12">
		<div class="card mb-3">
			<div class="bg-holder d-none d-lg-block bg-card" style="background-image:url('<?php echo e(asset('media/illustrations/corner-4.png')); ?>');">
			</div>
			<!--/.bg-holder-->
			<div class="card-body">
				<div class="row">
					<div class="col-lg-8">
						<h3 class="mb-0">Debit Voucher</h3>

						<!-- Breadcrumb -->
						<nav aria-label="breadcrumb fs-1">
							<ol class="breadcrumb">
								<!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->

								<?php echo e($link = ""); ?>

								<?php for($i = 1; $i <= count(Request::segments()); $i++): ?>

									<?php if($i < count(Request::segments()) & $i > 0): ?>
										<!-- <?php echo e($link .= "/" . Request::segment($i)); ?> -->
										<li class="breadcrumb-item">
											<a href="<?= $link ?>"><?php echo e(ucwords(str_replace('-',' ',Request::segment($i)))); ?></a>
										</li>
										<i class="fas fa-long-arrow-alt-right m-1"></i>

										<?php else: ?> <?php echo e(ucwords(str_replace('-',' ',Request::segment($i)))); ?>

									<?php endif; ?>
								<?php endfor; ?>
				          		<!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
				          		<li class="breadcrumb-item active" aria-current="page">Data</li> -->
				          	</ol>
	      				</nav>
	  				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<form action="">
					<div class="form-row ">
						<div class="col-sm-2">
	            			<label for="datepicker">Transaction Date</label>
	            			<input class="form-control datetimepicker flatpickr-input" id="dpVoucherDate" type="text">
          				</div>

						<div class="col-sm-2">
							<label>Select Payment Account</label>
							<select class="form-control selectpicker" id="accParent">
							    <option>Cash In Hand</option>
							    <option>Banks</option>
							    <option>Receivables</option>
							    <option>Paybale</option>
							    <option>General Expenses</option>
							    <option>Revenues</option>
							</select>
						</div>

						<div class="col-sm-2">
							<label>Select Controlling Account</label>
							<select class="form-control selectpicker" id="accControlling">
							    <option>Assets</option>
							    <option>Liabilites</option>
							    <option>Equities</option>
							    <option>Revenues</option>
							    <option>Expenses</option>
							</select>
						</div>

						<div class="col-sm-6">
							<label>Description</label>
							<input type="text" name="txtContAcc" class="form-control">
						</div>
					</div>


					<div class="row mx-1 my-3">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive fs--1">
										<div class="d-flex">
											<p class="text-warning">- Press Enter On Amount Column to Add More Debit Account or Use Button</p>
											<button class="btn btn-falcon-default btn-sm rounded-capsule ml-auto my-2 mx-1" type="button" id="btnAddAccount">
												<span class="fas fa-plus mr-1" data-fa-transform="shrink-3"></span>Add Debit Account
											</button>
										</div>

					            	<table id="tblTransEntry" class="table table-striped border">
					                <thead class="bg-200 text-900">
					                	<tr>
					                   	<th class="border-0" width="20%">Debited Account</th>
					                   	<th class="border-0">Particular</th>
					                   	<th class="border-0" width="20%">Amount (DR)</th>
					                   	<th class="border-0 text-center" width="10%">Action</th>
					                  </tr>
					                </thead>
					                <tbody>
					                  	<tr>
					                    	<td class="align-middle">
					                      	<select class="form-control form-control-sm selectpicker">
											    <option>Cash In Hand</option>
											    <option>Banks</option>
											    <option>Receivables</option>
											    <option>Paybale</option>
											    <option>General Expenses</option>
											    <option>Revenues</option>
											</select>
					                    	</td>
						                    <td class="align-middle">
						                    	<input class="form-control form-control-sm" name="txtParticular" type="text" placeholder="Enter Particular">
						                    </td>
						                    <td class="align-left">
						                    	<input class="form-control form-control-sm transAmount" name="txtEntryAmnt" type="number" placeholder="Enter Amount">
						                    </td>
						                    <td class="deleteRow align-middle text-center cursor-pointer">
						                    	<span class="badge rounded-capsule badge-soft-danger cursor-pointer">Delete
						                    		<span class="ml-1 fas fa-window-close fa-lg"></span>
						                    	</span>
						                    </td>
					                  </tr>
					                </tbody>
					                <tfoot class="bg-200">
									    <tr>
									      <th></th>
									      <th class="text-right">Total Debit Amount</th>
									      <th id="txtTrnsTotal"></th>
									      <th></th>
									    </tr>
								  	</tfoot>
					           	 	</table>
					            </div>
								</div>
							</div>
						</div>
					</div>

					<div class="d-flex flex-row-reverse mr-3">
						<button type="button" class="btn btn-success mr-1 mb-1 w-25">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/accounts\debitVoucher.blade.php ENDPATH**/ ?>
