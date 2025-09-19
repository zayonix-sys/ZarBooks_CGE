$(document).ready(function(){

// Start Chart of Account View Script
	var assTbl = $('#assDataTable').DataTable();
	$('#assTotal').text(assTbl.column(3).data().sum());

	var libTbl = $('#libDataTable').DataTable();

// End Chart of Account View Script

// -------------------------------------------------------------------------- //

// Start Debit Voucher View Script

	//Inatialize Select Picker
	$('select').select2({
		theme: 'bootstrap4',
	});

	//Inatialize Transcation Date Picker
	$('#dpVoucherDate').flatpickr({
		dateFormat: 'd-M-Y',
		defaultDate: 'today'
	});

	//Add New Row in Entry Table
	$('#tblTransEntry').on('keyup', 'td:nth-child(3)', function(){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13')
		{
			$tableBody = $('#tblTransEntry').find("tbody"),
			$trLast = $tableBody.find("tr:last"),
			//$trNew = $trLast.clone();

			$trLast.after('<tr><td class="align-middle"><select class="form-control selectpicker"><option>Cash In Hand</option><option>Banks</option><option>Receivables</option><option>Paybale</option><option>General Expenses</option><option>Revenues</option></select></td><td class="align-middle"><input class="form-control form-control-sm" name="txtParticular" type="text" placeholder="Enter Particular"></td><td class="align-left"><input class="form-control form-control-sm transAmount" name="txtEntryAmnt" type="number" placeholder="Enter Amount"></td><td class="deleteRow align-middle text-center cursor-pointer"><span class="badge rounded-capsule badge-soft-danger cursor-pointer">Delete<span class="ml-1 fas fa-window-close fa-lg"></span></span></td></tr>');
		}
		$('.selectpicker').select2();
		calculateRowSum();
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
	    	$('#txtTrnsTotal').text(sum);
		});
	}
// End Debit Voucher View Script

});

