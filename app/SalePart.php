<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalePart extends Model
{
	//For 
	protected $table = 'tbl_sale_parts';

	protected $fillable = ['bill_no', 'quantity', 'salesmanname', 'date', 'product_id', 'total_price', 'price', 'customer_id', 'custom_field'];
}
