<?php
/*
|--------------------------------------------------------------------------
| Application Routes for the Companies
|--------------------------------------------------------------------------
*/

/**********************************************************************************
**********************************   COMPANIES   **********************************
**********************************************************************************/

/*****************************
**   ROUTES FOR THE VIEWS   **
*****************************/


/* Esta ruta muestra la vista en la que se listan todas las compañías */
/* This route shows the view in which all the companies are listed */
$router->get('companies', ['as' => 'companies', function (Illuminate\Http\Request $request) use ($router) {

	if ($request->session()->has('user')) {

		$companies 			= DB::select("SELECT
											c.id_company, c.tradename, c.business_name,
										    GROUP_CONCAT(csn.sector_type_key, ',') AS sector_type_name,
										    GROUP_CONCAT(csn.norm_key, ' || ', csn.norm_name, ',') AS norms
										FROM tbl_companies c
										LEFT JOIN trns_companies_sectors_norms csn ON csn.id_company = c.id_company
										WHERE c.status = ? GROUP BY c.id_company", [1]);
		$companies_types 	= app('db')->table('cat_companies_types')->where('status',1)->get();

		$countries 			= app('db')->table('cat_countries')->where('status',1)->get();
		$federal_entities 	= app('db')->table('cat_federal_entity')->where('status',1)->get();
		$phones 			= app('db')->table('tbl_companies_phones')->where('status',1)->get();
		// $addresses 			= app('db')->table('tbl_companies_addresses')->where('status',1)->get();
		// var_dump($addresses);
		// exit();

		$heat_labels 		= array("1"=>'Ninguno', "2"=>'Customer', "3"=>'Hot lead', "4"=>'Waem lead', "5"=>'Cold lead');
		$owners 			= array("1"=>'Ninguno', "2"=>'Customer', "3"=>'Hot lead', "4"=>'Waem lead', "5"=>'Cold lead');

		$economic_sector_coding	= app('db')->table('cat_economic_sector_coding')->where('status',1)->get();
		$types_service			= app('db')->table('cat_types_service')->where('status',1)->get();

		return view('companies', [
									'companies'					=> $companies,
									'companies_types'			=> $companies_types,
									'countries'					=> $countries,
									'federal_entities'			=> $federal_entities,
									'phones'					=> $phones,
									// 'addresses'					=> $addresses,
									'heat_labels'				=> $heat_labels,
									'owners'					=> $owners,
									'economic_sector_coding'	=> $economic_sector_coding,
									'types_service'				=> $types_service,
								]);
	}
	else {

		return redirect()->route('login', ['error' => 2]);
	}
}]);

/* Esta ruta muestra la información de cada compañia, esta puede ser editada en cada uno de los tab */
/* This route shows the information of each company, this can be edited in each of the tabs */
$router->get('company/edit/{id}', ['as' => 'edit_company', function (Illuminate\Http\Request $request, $id) use ($router) {

	if ($request->session()->has('user'))
	{
		$basics			= app('db')->table('tbl_companies')->where('id_company',$id)->first();
		$companies_types 	= app('db')->table('cat_companies_types')->where('status',1)->get();
		$addresses		= app('db')
							->table('tbl_companies_addresses')
							->leftJoin(
								'cat_companies_siteactivities',
									'tbl_companies_addresses.id_company_siteactivity',
									'=',
									'cat_companies_siteactivities.id_company_siteactivity'
							)
							->select(
								'tbl_companies_addresses.*',
								'cat_companies_siteactivities.name AS name_site'
							)
							->where([
								['tbl_companies_addresses.id_company', $id],
								['tbl_companies_addresses.status', '1']
							])
							->orderby('tbl_companies_addresses.alias')
							->get();
		$phones			= app('db')->table('tbl_companies_phones')->where([['id_company', $id],['status', '1']])->get();
		$socialreason = app('db')->table('tbl_companies_socialreason')->where([['id_company', $id],['status', '1']])->get();
		$countries		= app('db')->table('cat_countries')->where('status', '1')->get();
		$entities		= app('db')->table('cat_federal_entity')->where('status', '1')->get();
		// $trns_sectors 	= app('db')->table('trns_companies_sectors')->where('status', '1')->get();
		// $heat_labels	= array("1"=>'Ninguno', "2"=>'Customer', "3"=>'Hot lead', "4"=>'Waem lead', "5"=>'Cold lead');
		$heat_labels	= app('db')->table('cat_companies_heatlabels')->where('status', '1')->get();
		$owners 		= app('db')
							->table('users')
							->select('id','name','lastname','mother_lastname','initials')
							->where('status',1)
							->get();

		$sectors_type 		= app('db')->table('cat_economic_sector_coding')->where('status',1)->get();

		$company_sectors_norms	= app('db')->table('trns_companies_sectors_norms')->where([['status',1],['id_company',$id]])->get();

		$types_service			= app('db')->table('cat_types_service')->where('status',1)->get();

		$site_activity			= app('db')->table('cat_companies_siteactivities')->where('status',1)->get();

		$factor_reduction		= app('db')->table('cat_reduction_factor')->where('status',1)->get();
		$factor_enlargement		= app('db')->table('cat_enlargement_factor')->where('status',1)->get();

		$scopes		= app('db')->table('tbl_companies_scopes')->where('status',1)->get();
		// var_dump($addresses);
		// exit();

		return view('companies/company_edit', [
												'cve_company'			=> $id-1,
												'basics'				=> $basics,
												'companies_types'		=> $companies_types,
												'addresses'				=> $addresses,
												'phones'				=> $phones,
												'socialreason'			=> $socialreason,
												'countries'				=> $countries,
												'entities'				=> $entities,
												// 'sectors'				=> $trns_sectors,
												'heat_labels'			=> $heat_labels,
												'owners'				=> $owners,
												'sectors_type'			=> $sectors_type,
												'company_sectors_norms'	=> $company_sectors_norms,
												'types_service'			=> $types_service,
												'site_activity'			=> $site_activity,
												'factor_reduction'		=> $factor_reduction,
												'factor_enlargement'	=> $factor_enlargement,
												'scopes'				=> $scopes
		]);
	}
	else
	{
		return redirect()->route('login', ['error' => 2]);
	}
}]);

/* Este template muestra los certificados de una compañia */
/* This template shows the certificates of a company */
$router->get('company/certificates/{id}', ['as' => 'company/certificates/', function (Illuminate\Http\Request $request, $id) use ($router) {

	if ($request->session()->has('user')) {

		$basics 		= app('db')->table('tbl_companies')->where('id_company',$id)->first();
		$addresses		= app('db')->table('tbl_companies_addresses')->where([['id_company', $id],['status', '1']])->get();
		$certificates 	= app('db')->table('trns_company_certificates')->where([['id_company', $id],['status', '1']])->get();
		$sectors_type 	= app('db')->table('cat_economic_sector_coding')->where('status',1)->get();
		$company_sectors_norms	= app('db')->table('trns_companies_sectors_norms')->where([['status',1],['id_company',1]])->get();

		return view('companies/company_certificates', [
														'basics'		=> $basics,
														'addresses'		=> $addresses,
														'certificates'	=> $certificates,
														'sectors_type'	=> $sectors_type,
														'sectors_norms'	=> $company_sectors_norms
													]);
	}
	else
	{
		return redirect()->route('login', ['error' => 2]);
	}
}]);


/***********************
**   ROUTES BACKEND   **
***********************/

/* COMPANIES => Company Address - Permite la inserción de un dirección (seccion Address) */
$router->post('backend/companies/company/sectors/sector_norm/insert/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$insert = app('db')
				->table('tbl_companies_addresses')
				->insert([
					'id_company'				=>	$request->input('cve_company'),
					'id_country'				=>	$request->input('country'),
					'id_federal_entity'			=>	$request->input('federal_entity'),
					'alias'						=>	$request->input('alias'),
					'street'					=>	$request->input('street'),
					'outdoor_number'			=>	$request->input('outdoor_number'),
					'interior_number'			=>	$request->input('interior_number'),
					'colony_town'				=>	$request->input('colony_town'),
					'delegation_municipality'	=>	$request->input('delegation_municipality'),
					'postal_code'				=>	$request->input('postal_code'),
					'between_streets'			=>	$request->input('between_streets'),
					'reference'					=>	$request->input('reference'),
					'start_date'				=>	$request->input('start_date'),
					'end_date'					=>	$request->input('end_date'),
					// ''	=>	$request->input(''),
					'creation_date'				=>	date('Y-m-d H:i:s'),
					'creator_user'				=>	$request->session()->get('user_id')
				]);

	$message = 'error|Error creating record!';

	if ($insert == 1) {

		$message = 'success|Successfully created record!';
	}

	$request->session()->put('system_notification', $message);

	return redirect()->route('company/edit/',['id'=>$id]);
});

/* COMPANIES => Company Address - Permite el borrado logico de una comapñia (seccion Address) */
$router->get('backend/companies/company/delete/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$update = app('db')
					->table('tbl_companies')
					->where('id_company',$id)
					->update([
						'status'			=>	0,
						'modification_date'	=>	date('Y-m-d H:i:s'),
						'modification_user'	=>	$request->session()->get('user_id')
					]);

	$message = 'error|Error creating record!';

	if ($update == 1) {

		$message = 'success|Successfully created record!';
	}

	$request->session()->put('system_notification', $message);

	return redirect()->route('companies');
});

/* COMPANIES => Company Phone - Permite el borrado logico de un numero telefonio (tab Phone) */
$router->get('backend/companies/company/phone/delete/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$update = app('db')
					->table('tbl_companies_phones')
					->where('id_company_phone',$id)
					->update([
						'status'			=>	0,
						'modification_date'	=>	date('Y-m-d H:i:s'),
						'modification_user'	=>	$request->session()->get('user_id')
					]);

	$message = 'error|Error creating record!';

	if ($update == 1) {

		$message = 'success|Successfully created record!';
		$return = array('result'=>'success', 'message'=>'Record successfully deleted!');
	}
	else {

		$message = 'error|Error updating registry!';
		$return = array('result'=>'error', 'message'=>'Error updating registry!');
	}

	$request->session()->put('system_notification', $message);

	return $return;
});

/**
 * Juan Francisco
 * Social reason tab
 * Esta URL Permite desactivar un registro en la tabla Social reason
 * this URL Allows deactivate a record in the Social reason table
 */
$router->get('backend/companies/company/socialreason/delete/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$update = app('db')
					->table('tbl_companies_socialreason')
					->where('id_company_socialreason',$id)
					->update([
						'status'			=>	0,
						'modification_date'	=>	date('Y-m-d H:i:s'),
						'modification_user'	=>	$request->session()->get('user_id')
					]);

	$message = 'error|Error creating record!';

	if ($update == 1) {

		$message = 'success|Successfully created record!';
		$return = array('result'=>'success', 'message'=>'Record successfully deleted!');
	}
	else {

		$message = 'error|Error updating registry!';
		$return = array('result'=>'error', 'message'=>'Error updating registry!');
	}

	$request->session()->put('system_notification', $message);

	return $return;
});

/*
*Juan Francisco
*Scope Tab
*Esta URL Permite desactivar un registro en la tabla Scope
*this URL Allows deactivate a record in the Scope table
*/

$router->get('backend/companies/company/scope/delete/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$update = app('db')
					->table('tbl_companies_scopes')
					->where('id_company_scope',$id)
					->update([
						'status'			=>	0,
						'modification_date'	=>	date('Y-m-d H:i:s'),
						'modification_user'	=>	$request->session()->get('user_id')
					]);

	$message = 'error|Error creating record!';

	if ($update == 1) {

		$message = 'success|Successfully created record!';
		$return = array('result'=>'success', 'message'=>'Record successfully deleted!');
	}
	else {

		$message = 'error|Error updating registry!';
		$return = array('result'=>'error', 'message'=>'Error updating registry!');
	}

	$request->session()->put('system_notification', $message);

	return $return;
});

/* COMPANIES => Company Address - Permite el borrado logico de una dirección (tab Address) */
$router->get('backend/companies/company/address/delete/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$update = app('db')
					->table('tbl_companies_addresses')
					->where('id_company_address',$id)
					->update([
						'status'			=>	0,
						'modification_date'	=>	date('Y-m-d H:i:s'),
						'modification_user'	=>	$request->session()->get('user_id')
					]);

	if ($update == 1) {

		$message 	= 'success|Register successfully updated!'; // Mensaje para mostrar en la js función getSystemNotifications()
		$return 	= array('result'=>'success', 'message'=>'Record successfully deleted!');
	}
	else {

		$message 	= 'error|Error updating registry!'; // Mensaje para mostrar en la js función getSystemNotifications()
		$return 	= array('result'=>'error', 'message'=>'Error updating registry!');
	}

	$request->session()->put('system_notification', $message);

	return $return;
});

/* COMPANIES => Company phone nuber - Permite la inserción de una compañia (seccion Basic) */
$router->post('backend/companies/phone_number/update', function (Illuminate\Http\Request $request) use ($router) {

	$id_company 	= $request->input('cve');
	$numbers 		= $request->input('numbers');
	$extension 		= $request->input('extension');
	$cadena_json 	= '[';

	$numbers = substr($numbers, 0, -1);
	$numbers = explode(',', $numbers);

	$extension = substr($extension, 0, -1);
	$extension = explode('|', $extension);

	foreach ($numbers as $key => $value) {

		$cadena_json .= '{"telephone_numbers": "'.$value.'","extension": "'.$extension[$key].'"},';
	}

	if($cadena_json != '[') {

		$cadena_json = substr($cadena_json, 0, -1);
	}

	$cadena_json = $cadena_json . ']';

	$edit = app('db')
				->table('tbl_companies')
				->where('id_company', $id_company)
				->update([
					'telephone_numbers' => $cadena_json,
					'modification_date'	=> date('Y-m-d H:i:s'),
					'modification_user'	=> $request->session()->get('user_id')
				]);

	if ($edit == 1) {

		$return = array('result'=>'success', 'message'=>'Register successfully updated!');
	}
	else {

		$return = array('result'=>'error', 'message'=>'Error updating registry!');
	}

	return response()->json($return);
});

/*
* Juan Francisco
* social reason tab
* COMPANIES => Social reason - this URL allow modify any existing social reason
* Esta URL permite modificar una razon social existente
*/
$router->post('backend/companies/company/socialreason/update/{id}', function (Illuminate\Http\Request $request,$id) use ($router) {

	$cve_company_phone 	= $request->input('cve_social_reason');
	$social_reason 	= $request->input('social_reason');
	$rfc 		= $request->input('rfc');
	$edit = app('db')
				->table('tbl_companies_socialreason')
				->where('id_company_socialreason', $cve_company_phone)
				->update([
					'socialreason' => $social_reason,
					'RFC' => $rfc,
					'modification_date'	=> date('Y-m-d H:i:s'),
					'modification_user'	=> $request->session()->get('user_id')
				]);

	if ($edit == 1) {

		$return = array('result'=>'success', 'message'=>'Register successfully updated!');
	}
	else {

		$return = array('result'=>'error', 'message'=>'Error updating registry!');
	}

	//return response()->json($return);
	return redirect()->route('edit_company',['id'=>$id]);
});

/*
*Juan Francisco
*Scope Tab
*Esta URL Permite modificar un registro en la tabla Scope
*this URL Allows modify a record in the Scope table
*/
$router->post('backend/companies/company/scope/update/{id}', function (Illuminate\Http\Request $request,$id) use ($router) {
	$flag = explode('|', $request->input('type_service'));
	$id_type_service =  $flag[0];
	$initials =  $flag[1];

	$id_company_scope 	= $request->input('id_company_scope');
	$scope 	= $request->input('scope');

	$edit = app('db')
				->table('tbl_companies_scopes')
				->where('id_company_scope', $id_company_scope)
				->update([
					'id_type_service' => $id_type_service,
					'scope' => $scope,
					'initials'=>$initials,
					'modification_date'	=> date('Y-m-d H:i:s'),
					'modification_user'	=> $request->session()->get('user_id')
				]);

	if ($edit == 1) {

		$return = array('result'=>'success', 'message'=>'Register successfully updated!');
	}
	else {

		$return = array('result'=>'error', 'message'=>'Error updating registry!');
	}

	//return response()->json($return);
	return redirect()->route('edit_company',['id'=>$id]);
});

/* COMPANIES => Company address - Permite la actualizacion de la(s) direccion(es) de una compañia (seccion Address) */
$router->post('backend/companies/address/update', function (Illuminate\Http\Request $request) use ($router) {

	$id_company = $request->input('cve');
	$address 	= $request->input('address');

	$edit = app('db')
				->table('tbl_companies')
				->where('id_company', $id_company)
				->update([
					'address' 			=> $address,
					'modification_date'	=> date('Y-m-d H:i:s'),
					'modification_user'	=> $request->session()->get('user_id')
				]);

	if ($edit == 1) {

		$return = array('result'=>'success', 'message'=>'Register successfully updated!');
	}
	else {

		$return = array('result'=>'error', 'message'=>'Error updating registry!');
	}

	return response()->json($return);
});

/* COMPANIES => CERTIFICATES - Permite la insercion de un nuevo certificado por compañia */
$router->post('backend/companies/company/certificates/insert', function(Illuminate\Http\Request $request) use ($router)
{
	$insert = app('db')
				->table('trns_company_certificates')
				->insert([
							'id_company'				=> $request->input('cve_company'),
							'id_company_sector_norm'	=> $request->input('id_sector_norm'),
							'name'						=> $request->input('certificate_name'),
							'code'						=> $request->input('certificate_code'),
							'address'					=> $request->input('certificate_address'),
							'expiration_date'			=> $request->input('certificate_expiration_date'),
							'creation_date'		=>	date('Y-m-d H:i:s'),
							'creator_user'		=>	$request->session()->get('user_id')
				]);

	$message = 'error|Error creating record!';

	if ($insert == 1) {

		$message = 'success|Successfully created record!';
	}

	$request->session()->put('system_notification', $message);

	return redirect()->route('company/certificates/',['id'=>$request->input('cve_company')]);
});

/* COMPANIES => Company - Permite la inserción de una nueva compañia (Companies) */
$router->post('backend/companies/company/create', function(Illuminate\Http\Request $request) use ($router)
{
	$id 	= '0';
	$insert = app('db')
				->table('tbl_companies')
				->insert([
					// 'id_type_company'	=>	$request->input('company_type'),
					'id_type_company'	=>	0,
					'id_heat_label'		=>	$request->input('heat_label'),
					// 'tradename'			=>	$request->input('tradename'),
					'business_name'		=>	$request->input('business_name'),
					'web_page'			=>	$request->input('web_page'),
					'size'				=>	$request->input('size'),
					'creation_date'		=>	date('Y-m-d H:i:s'),
					'creator_user'		=>	$request->session()->get('user_id')
				]);

	$message = 'error|Error creating record!';

	if ($insert == 1) {

		$id = app('db')->table('tbl_companies')->get()->last(); // Se recupera el id asignado a la nueva compañia

		$message = 'success|Successfully created record!';

		if( trim( $request->input('cell_phone') ) != '') {

			$insert = app('db')
				->table('tbl_companies_phones')
				->insert([
					'id_company'	=>	$id->id_company,
					'number'		=>	trim( $request->input('cell_phone') ),
					'creation_date'		=>	date('Y-m-d H:i:s'),
					'creator_user'		=>	$request->session()->get('user_id')
				]);
		}

		if(
			$request->input('federal_entity') != '' ||
			trim( $request->input('delegation_municipality') ) != '' ||
			trim( $request->input('colony_town') ) != '' ||
			trim( $request->input('street') ) != ''
		) {

			$insert = app('db')
				->table('tbl_companies_addresses')
				->insert([
					'id_company'				=>	$id->id_company,
					'id_federal_entity'			=>	$request->input('federal_entity'),
					'street'					=>	trim( $request->input('street') ),
					'colony_town'				=>	trim( $request->input('colony_town') ),
					'delegation_municipality'	=>	trim( $request->input('delegation_municipality') ),
					'creation_date'				=>	date('Y-m-d H:i:s'),
					'creator_user'				=>	$request->session()->get('user_id')
				]);
		}
	}

	$request->session()->put('system_notification', $message);

	return redirect()->route('company/edit/',['id'=>$id->id_company]);
});

/* Esta ruta BACKEND permite la actualización de un numero telefonico de una compañia */
/* This BACKEND route allows the updating of a telephone number of a company */
$router->post('backend/companies/company/phone/update/{id_company}/{id_phone}', function(Illuminate\Http\Request $request, $id_company,$id_phone) use ($router)
{
	$update = app('db')
				->table('tbl_companies_phones')
				->where('id_company_phone',$id_phone)
				->update([
					'number'			=>	$request->input('number'),
					'extension'			=>	$request->input('extension'),
					'modification_date'	=>	date('Y-m-d H:i:s'),
					'modification_user'	=>	$request->session()->get('user_id')
				]);

	$message = 'error|Registry update failed!';

	if ($update == 1) {

		$message = 'success|Successfully created record!';
	}
	else {

		$message = 'error|Error updating registry!';
	}

	$request->session()->put('system_notification', $message);

	// return redirect()->route('company/edit/', ['id' => $id_company]);
	return redirect()->route('edit_company', ['id' => $id_company]);
});

/* 
Juan Francisco
Esta ruta BACKEND permite la actualización razon social de una compañia cambiar ruta */
/* This BACKEND route allows the updating of a telephone number of a company */
$router->post('backend/companies/company/socialreason/update/{id_company}/{id_phone}', function(Illuminate\Http\Request $request, $id_company,$id_phone) use ($router)
{
	$update = app('db')
				->table('tbl_companies_phones')
				->where('id_company_phone',$id_phone)
				->update([
					'number'			=>	$request->input('number'),
					'extension'			=>	$request->input('extension'),
					'modification_date'	=>	date('Y-m-d H:i:s'),
					'modification_user'	=>	$request->session()->get('user_id')
				]);

	$message = 'error|Registry update failed!';

	if ($update == 1) {

		$message = 'success|Successfully created record!';
	}
	else {

		$message = 'error|Error updating registry!';
	}

	$request->session()->put('system_notification', $message);

	// return redirect()->route('company/edit/', ['id' => $id_company]);
	return redirect()->route('edit_company', ['id' => $id_company]);
});

/* COMPANIES => Company Address - Permite la inserción de un dirección (seccion Address) */
$router->post('backend/companies/company/phone/insert/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$insert = app('db')
				->table('tbl_companies_phones')
				->insert([
					'id_company'	=>	$request->input('cve_company'),
					'number'		=>	$request->input('number'),
					'extension'		=>	$request->input('extension'),
					'creation_date'	=>	date('Y-m-d H:i:s'),
					'creator_user'	=>	$request->session()->get('user_id')
				]);

	$message = 'error|Error creating record!';

	if ($insert == 1) {

		$message = 'success|Successfully created record!';
	}

	$request->session()->put('system_notification', $message);

	// return redirect()->route('company/edit/{id}',['id'=>$request->input('cve_company')]);
	return redirect()->route('edit_company',['id'=>$request->input('cve_company')]);
});

/*
* Juan Francisco
* social reason tab
* COMPANIES => Social reason - this URL allow insert new social reason
* Esta URL permite insertar una nueva razon social
*/
$router->post('backend/companies/company/socialreason/insert/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$insert = app('db')
				->table('tbl_companies_socialreason')
				->insert([
					'id_company'	=>	$request->input('cve_company'),
					'socialreason'		=>	$request->input('social_reason'),
					'rfc'		=>	$request->input('rfc'),
					'creation_date'	=>	date('Y-m-d H:i:s'),
					'creator_user'	=>	$request->session()->get('user_id')
				]);

	$message = 'error|Error creating record!';

	if ($insert == 1) {

		$message = 'success|Successfully created record!';
	}

	$request->session()->put('system_notification', $message);

	// return redirect()->route('company/edit/{id}',['id'=>$request->input('cve_company')]);
	return redirect()->route('edit_company',['id'=>$request->input('cve_company')]);
});

// Juan Francisco
// scope tab
// this URL allow insert new register to scope table
// esta URL permite insertar un nuevo registro en la tabla de alcance
$router->post('backend/companies/company/scopes/insert/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$flag = explode('|', $request->input('type_service'));
	$id_type_service =  $flag[0];
	$initials =  $flag[1];
	$insert = app('db')
				->table('tbl_companies_scopes')
				->insert([
					'id_company'	=>	$id,
					'id_type_service'		=>	$id_type_service,
					'initials'		=>	$initials,
					'scope'			=> 	$request->input('scope'),
					'creation_date'	=>	date('Y-m-d H:i:s'),
					'creator_user'	=>	$request->session()->get('user_id')
				]);

	$message = 'error|Error creating record!';

	if ($insert == 1) {

		$message = 'success|Successfully created record!';
	}

	$request->session()->put('system_notification', $message);

	// return redirect()->route('company/edit/{id}',['id'=>$request->input('cve_company')]);
	return redirect()->route('edit_company',['id'=>$request->input('cve_company')]);
});

/* COMPANIES => Company Address - Permite la inserción de un dirección (seccion Address) */
$router->post('backend/companies/company/address/insert/{id}', function(Illuminate\Http\Request $request, $id) use ($router)
{
	$fiscal_address 		= ($request->input('fiscal_address') == 'on' ? 1 : 0);
	$repetitive_activity 	= ($request->input('repetitive_activity') == 'on' ? 1 : 0);

	if( $fiscal_address == '1' ) {
		$update = app('db')
					->table('tbl_companies_addresses')
					->where([
								['id_company',$id],
								['status',1]
							])
					->update(['fiscal_address' => '0']);
	}

	$insert = app('db')
				->table('tbl_companies_addresses')
				->insert([
					'id_company'				=>	$request->input('cve_company'),
					'id_country'				=>	$request->input('country'),
					'id_federal_entity'			=>	$request->input('federal_entity'),
					'id_company_siteactivity'	=>	$request->input('site_activities'),
					'fiscal_address'			=>	$fiscal_address,
					'repetitive_activity'		=>	$repetitive_activity,
					'total_repetitive_activity'	=>	$request->input('total_repetitive_activities'),
					'name_repetitive_activity'	=>	$request->input('name_repetitive_activities'),
					'alias'						=>	$request->input('alias'),
					'size'						=>	$request->input('size'),
					'street'					=>	$request->input('street'),
					'outdoor_number'			=>	$request->input('outdoor_number'),
					'interior_number'			=>	$request->input('interior_number'),
					'colony_town'				=>	$request->input('colony_town'),
					'delegation_municipality'	=>	$request->input('delegation_municipality'),
					'postal_code'				=>	$request->input('postal_code'),
					'between_streets'			=>	$request->input('between_streets'),
					'reference'					=>	$request->input('reference'),
					'creation_date'				=>	date('Y-m-d H:i:s'),
					'creator_user'				=>	$request->session()->get('user_id')
				]);

	$message = 'error|Error creating record!';

	if ($insert == 1) {

		$message = 'success|Successfully created record!';
	}

	$request->session()->put('system_notification', $message);

	return redirect()->route('edit_company',['id'=>$id]);
});

/* COMPANIES => Company Address - Permite la edición de una direccion (tab Address) */
$router->post('backend/companies/company/address/update', function(Illuminate\Http\Request $request) use ($router)
{
	$fiscal_address 		= ( $request->input('fiscal_address_edit') == 'on' ? '1' : '0' );
	$fixed_site_edit 		= ( $request->input('fixed_site_edit') == 'on' ? 1 : 0 );
	$repetitive_activity 	= ( $request->input('repetitive_activity_edit') == 'on' ? 1 : 0 );
	$alias 					= ( $request->input('alias_edit') == null ? 'Principal' : $request->input('alias_edit') );

	if($fiscal_address == '1') {
		$update = app('db')
					->table('tbl_companies_addresses')
					->where([
						['id_company',$request->input('company_cve')],
						['status',1],
							])
					->update(['fiscal_address' => '0']);
	}

	$update = app('db')
				->table('tbl_companies_addresses')
				->where('id_company_address',$request->input('site_cve'))
				->update([
					// 'id_company'				=>	$request->input('company_cve'),
					'id_country'				=>	$request->input('country_edit'),
					'id_federal_entity'			=>	$request->input('federal_entity_edit'),
					'id_company_siteactivity'	=>	$request->input('site_activities_edit'),
					'fiscal_address'			=>	$fiscal_address,
					'fixed_site'				=>	$fixed_site_edit,
					'repetitive_activity'		=>	$repetitive_activity,
					'total_repetitive_activity'	=>	$request->input('total_repetitive_activities_edit'),
					'name_repetitive_activity'	=>	$request->input('name_repetitive_activities_edit'),
					'alias'						=>	$alias,
					'size'						=>	$request->input('size_edit'),
					'street'					=>	$request->input('street_edit'),
					'outdoor_number'			=>	$request->input('outdoor_number_edit'),
					'interior_number'			=>	$request->input('interior_number_edit'),
					'colony_town'				=>	$request->input('colony_town_edit'),
					'delegation_municipality'	=>	$request->input('delegation_municipality_edit'),
					'postal_code'				=>	$request->input('postal_code_edit'),
					'between_streets'			=>	$request->input('between_streets_edit'),
					'reference'					=>	$request->input('reference_edit'),
					'modification_date'			=>	date('Y-m-d H:i:s'),
					'modification_user'			=>	$request->session()->get('user_id')
				]);

	$message = 'error|Registry update failed!';

	if ($update == 1) {

		$message = 'success|Successfully created record!';
	}
	else {

		$message = 'error|Error updating registry!';
	}

	$request->session()->put('system_notification', $message);

	return redirect()->route('edit_company', ['id' => $request->input('company_cve')]);
});


/*******************
**   ROUTES API   **
*******************/

/* En esta ruta API se crea una nueva empresa */
/* In this API route a new company is created */
$router->post('api/v1/company/basic/create', function(Illuminate\Http\Request $request) use ($router) {

	$business_name 	= $request->input('business_name');
	$heat_label 	= $request->input('heat_label');
	$owner 			= $request->input('owner');
	$web_page 		= $request->input('web_page');
	// $size 			= $request->input('size');

	$insert = app('db')
				->table('tbl_companies')
				->insert([
					'id_heat_label'	=>	$heat_label,
					'business_name'	=>	$business_name,
					// 'id_size'		=>	$size,
					'id_owner'		=> $owner,
					'web_page'		=>	$web_page,
					'creation_date'	=>	date('Y-m-d H:i:s'),
					'creator_user'	=>	$request->session()->get('user_id')
				]);

	$return = array('result'=>'error', 'message'=>'Error creating record!');

	if ($insert == 1) {

		$idc 	= app('db')->table('tbl_companies')->get()->last(); // Recupera el id asignado
		$return = array('result'=>'success', 'message'=>'Successfully created record!', 'action'=>'create', 'id'=>$idc->id_company);
	}

	return response()->json($return);
});

/* En esta ruta API se actualiza una compañia */
/* In this API route a company is updated */
$router->post('api/v1/company/basic/update', function(Illuminate\Http\Request $request) use ($router) {

	$id_company 	= $request->input('cve_company');
	$business_name 	= $request->input('business_name');
	$heat_label 	= $request->input('heat_label');
	$owner 			= $request->input('owner');
	$web_page 		= $request->input('web_page');
	$size 			= $request->input('size');

	$update = app('db')
				->table('tbl_companies')
				->where('id_company',$id_company)
				->update([
					'id_heat_label'		=> $heat_label,
					'business_name'		=> $business_name,
					'id_size'			=> $size,
					'id_owner'			=> $owner,
					'web_page'			=> $web_page,
					'modification_date'	=> date('Y-m-d H:i:s'),
					'modification_user'	=> $request->session()->get('user_id')
				]);

	$return = array('result'=>'error', 'message'=>'Error updating registry!');

	if ($update == 1) {

		$return = array('result'=>'success', 'message'=>'Registration successfully updated!', 'action'=>'update');
	}

	return response()->json($return);
});

/* En esta ruta API se asigna un numero telefonico */
/* In this API route a phone number is assigned */
$router->post('api/v1/company/phone/create', function(Illuminate\Http\Request $request) use ($router) {

	$id_company = $request->input('cve_company');
	$number 	= $request->input('number');
	$extension 	= $request->input('extension');

	$insert = app('db')
				->table('tbl_companies_phones')
				->insert([
					'id_company'	=>	$id_company,
					'number'		=>	$number,
					'extension'		=>	$extension,
					'creation_date'	=>	date('Y-m-d H:i:s'),
					'creator_user'	=>	$request->session()->get('user_id')
				]);

	$return = array('result'=>'error', 'message'=>'Error creating record!');

	if ($insert == 1) {

		$idp 	= app('db')->table('tbl_companies_phones')->get()->last(); // Recupera el id asignado
		$return = array('result'=>'success', 'message'=>'Successfully created record!', 'action'=>'create', 'id'=>$idp->id_company_phone);
	}

	return response()->json($return);
});

/* En esta ruta API se actualiza un numero telefonico */
/* In this API route a phone number is updated */
$router->post('api/v1/company/phone/update', function(Illuminate\Http\Request $request) use ($router) {

	$id_company_phone 	= $request->input('cve_phone');
	// $id_company = $request->input('cve_company');
	$number 			= $request->input('number');
	$extension 			= $request->input('extension');

	$update = app('db')
				->table('tbl_companies_phones')
				->where('id_company_phone',$id_company_phone)
				->update([
					'number' 	=> $number,
					'extension'	=> $extension
				]);

	$return = array('result'=>'error', 'message'=>'Error updating registry!');

	if ($update == 1) {

		$return = array('result'=>'success', 'message'=>'Registration successfully updated!', 'action'=>'update');
	}

	return response()->json($return);
}) ;

/* En esta ruta API se asigna un numero telefonico */
/* In this API route a phone number is assigned */
$router->post('api/v1/company/address/create', function(Illuminate\Http\Request $request) use ($router) {

	$insert = app('db')
				->table('tbl_companies_addresses')
				->insert([
					// 'id_company_address'		: $("cve_address").val(),
					'id_company'				=> $request->input("cve_company"),
					'alias'						=> 'Principal',
					'size'						=> $request->input("size"),
					'street'					=> $request->input("street"),
					'outdoor_number'			=> $request->input("outdoor_number"),
					'interior_number'			=> $request->input("interior_number"),
					'postal_code'				=> $request->input("postal_code"),
					'colony_town'				=> $request->input("colony_town"),
					'delegation_municipality'	=> $request->input("delegation_municipality"),
					'id_federal_entity'			=> $request->input("cve_federal_entity"),
					'id_country'				=> $request->input("cve_country"),
					'between_streets'			=> $request->input("between_streets"),
					'reference'					=> $request->input("reference")
				]);

	$return = array('result'=>'error', 'message'=>'Error creating record!');

	if ($insert == 1) {

		$idp 	= app('db')->table('tbl_companies_addresses')->get()->last(); // Recupera el id asignado
		$return = array('result'=>'success', 'message'=>'Successfully created record!', 'action'=>'create', 'id'=>$idp->id_company_address);
	}

	return response()->json($return);
});

/* En esta ruta API se actualiza un numero telefonico */
/* In this API route a phone number is updated */
$router->post('api/v1/company/address/update', function(Illuminate\Http\Request $request) use ($router) {

	$update = app('db')
				->table('tbl_companies_addresses')
				->where('id_company_address',$request->input("cve_address"))
				->update([
					// 'id_company_address'		: $("cve_address").val(),
					// 'id_company'				=> $request->input("cve_company"),
					'size'						=> $request->input("size"),
					'street'					=> $request->input("street"),
					'outdoor_number'			=> $request->input("outdoor_number"),
					'interior_number'			=> $request->input("interior_number"),
					'postal_code'				=> $request->input("postal_code"),
					'colony_town'				=> $request->input("colony_town"),
					'delegation_municipality'	=> $request->input("delegation_municipality"),
					'id_federal_entity'			=> $request->input("cve_federal_entity"),
					'id_country'				=> $request->input("cve_country"),
					'between_streets'			=> $request->input("between_streets"),
					'reference'					=> $request->input("reference")
				]);

	$return = array('result'=>'error', 'message'=>'Error updating registry!');

	if ($update == 1) {

		$return = array('result'=>'success', 'message'=>'Registration successfully updated!', 'action'=>'update');
	}

	return response()->json($return);
}) ;

/* En esta ruta API se el detalle de una compañia */
/* In this API route I know the detail of a company */
$router->post('api/v1/company/details', function(Illuminate\Http\Request $request) use ($router) {

	$id_company = $request->input('id');

	$details['company']	= app('db')->table('tbl_companies')->where([['status',1],['id_company',$id_company]])->get();
	$details['address'] = app('db')
							->table('tbl_companies_addresses')
							->where([
										['status',1],
										['id_company',$id_company],
										['alias','Principal']
									])
							->get();
	$details['sitios'] = app('db')
							->table('tbl_companies_addresses')
							->where([
										['status',1],
										['id_company',$id_company],
										['alias','!=','Principal']
									])
							->get();
	$details['servicios'] = app('db')
							->table('tbl_companies_addresses')
							->where([
										['status',1],
										['id_company',$id_company],
										['alias','!=','Principal']
									])
							->get();

	return response()->json($details);
}) ;

/* Esta ruta API agrega una nueva compañia desde wl wizard de Deals */
/* This API route adds a new company from the Deals wizard */
$router->post('api/v1/companies/company/add', function(Illuminate\Http\Request $request) use ($router) {

	// var_dump( $request->input('sectorization') );
	// exit();

	$return = array('result'=>'error', 'message'=>'Error creating record!');

	$id_company 			= '0';
	$id_company_phone 		= '0';
	$id_company_address		= '0';
	$id_person 				= '0';
	$id_person_company		= '0';
	$sectorization_insert 	= '0';

	/*** Inserción de una compañia ***/
	 $insertCompany = app('db')
						->table('tbl_companies')
						->insert([
							'business_name'=> $request->input('company'),
							'id_heat_label'=> $request->input('heat_label'),
							// 'id_size'=> $request->input('size'),
							'id_owner'=> $request->input('owner'),
							'creation_date'	=>	date('Y-m-d H:i:s'),
							'creator_user'	=>	$request->session()->get('user_id')
						]);


	if( $insertCompany == 1) {

		$id_company = app('db')->table('tbl_companies')->get()->last();
		$id_company = $id_company->id_company;
		$return 	= array(
								'result'=>'success',
								'message'=>'Successfully created record!',
								'action'=>'create'
							);
	}

	/*** Inserción de un número de telefono de una compañia ***/
	if( $id_company != '0' && $request->input('number') != '') {

		$insertPhoneC = app('db')
							->table('tbl_companies_phones')
							->insert([
								'id_company'	=> $id_company,
								'number'		=> $request->input('number'),
								'extension'		=> $request->input('extension'),
								'creation_date'	=> date('Y-m-d H:i:s'),
								'creator_user'	=> $request->session()->get('user_id')
							]);

		if( $insertPhoneC == 1) {

			$id_company_phone = app('db')->table('tbl_companies_phones')->get()->last();
			$id_company_phone = $id_company_phone->id_company_phone;
		}
	}

	/*** Inserción de la dirección de la compañia ***/
	if( $id_company != '0') {

		$insertCompanyAddress = app('db')
		->table('tbl_companies_addresses')
		->insert([
			'id_company'		=> $id_company,
			'id_country'		=> $request->input('heat_label'),
			'id_federal_entity'	=> $request->input('size'),
			'alias'				=> 'principal',
			'size'				=> $request->input('size'),
			'creation_date'		=> date('Y-m-d H:i:s'),
			'creator_user'		=> $request->session()->get('user_id')
		]);


		if( $insertCompanyAddress == 1) {

		$id_company = app('db')->table('tbl_companies')->get()->last();
		$id_company = $id_company->id_company;
		$return 	= array(
					'result'=>'success',
					'message'=>'Successfully created record!',
					'action'=>'create'
				);
		}
	}

	/*** Inserción de una persona ***/
	$insertPerson = app('db')
						->table('tbl_persons')
						->insert([
							'name'				=> $request->input('name'),
							'lastname'			=> $request->input('lastname'),
							'mother_lastname'	=> $request->input('mother_lastname'),
							'landline'			=> $request->input('lastname'),
							'cell_phone'		=> $request->input('cell_phone'),
							'email_1'			=> $request->input('email'),
							'creation_date'		=> date('Y-m-d H:i:s'),
							'creator_user'		=> $request->session()->get('user_id')
						]);

	if( $insertPerson == 1) {

		$id_person = app('db')->table('tbl_persons')->get()->last();
		$id_person = $id_person->id_person;
	}

	/*** Inserción de la relación entre una compañia y una persona ***/
	if( $id_company != '0' && $id_person != '0' ) {

		// echo $id_company."<br>";
		// echo $id_person."<br>";
		// echo date('Y-m-d H:i:s')."<br>";
		// echo $request->session()->get('user_id')."<br>";
		// exit("dddddddddddddddddddddd");

		$insertCompanyPerson = app('db')
									->table('trns_companies_persons')
									->insert([
										'id_company'	=> $id_company,
										'id_person'		=> $id_person,
										'creation_date'	=> date('Y-m-d H:i:s'),
										'creator_user'	=> $request->session()->get('user_id')
									]);

		if( $insertCompanyPerson == 1) {

			$id_person_company = app('db')->table('trns_companies_persons')->get()->last();
			$id_person_company = $id_person_company->id_companies_persons;
		}
	}

	/*** Inserción de la relación entre una compañia y sus normas (sectorization) ***/

	$sectorization = $request->input('sectorization');

	if( $id_company != '0' && sizeof( $sectorization ) > 0 ) {

		foreach ($sectorization as $key => $value) {

			$insertSectorization = app('db')
									->table('trns_companies_sectors_norms')
									->insert([
										'id_company'		=> $id_company,
										'id_type_service'	=> $value["type_service_cve"],
										'id_sector_type'	=> $value["sector_type"],
										'id_sector'			=> $value["sector_cve"],
										'id_norm'			=> $value["norm_cve"],
										'type_service_key'	=> $value["type_service_key"],
										'type_service_name'	=> $value["type_service_name"],
										// 'sector_type_key'	=> $value[""],
										'sector_type_name'	=> $value["sector_type_name"],
										'sector_name'		=> $value["sector_name"],
										'norm_key'			=> $value["norm_code"],
										'norm_name'			=> $value["norm_name"],
										'creation_date'		=> date('Y-m-d H:i:s'),
										'creator_user'		=> $request->session()->get('user_id')
									]);

			if( $insertSectorization == 1) {

				// $insertSectorization = app('db')->table('tbl_companies_phones')->get()->last();
				$sectorization_insert = 'ok';
			}
		}
	}

	$return['company']			= $id_company;
	$return['company_phone'] 	= $id_company_phone;
	$return['person']		 	= $id_person;
	$return['person_company']	= $id_person_company;
	$return['sectorization']	= $sectorization_insert;

	return response()->json($return);
});

/* Esta ruta API devuelve el detalle un sitio  */
/* This API route returns the detail of a site */
$router->post('api/v1/sectorization/company/site/get', function(Illuminate\Http\Request $request) use ($router)
{
	$id_company 	= $request->input('cve_company');
	$id_site	 	= $request->input('cve_site');

	$site = app('db')
				->table('tbl_companies_addresses')
				->leftjoin('cat_countries','tbl_companies_addresses.id_country','=','cat_countries.id_country')
				->leftjoin('cat_federal_entity','tbl_companies_addresses.id_federal_entity','=','cat_federal_entity.id_federal_entity')
				->select(
							'tbl_companies_addresses.id_company_address',
							'tbl_companies_addresses.id_company',
							'tbl_companies_addresses.id_company_siteactivity',
							'tbl_companies_addresses.fixed_site',
							'tbl_companies_addresses.fiscal_address',
							'tbl_companies_addresses.repetitive_activity',
							'tbl_companies_addresses.total_repetitive_activity',
							'tbl_companies_addresses.name_repetitive_activity',
							'tbl_companies_addresses.alias',
							'tbl_companies_addresses.size',
							'tbl_companies_addresses.street',
							'tbl_companies_addresses.outdoor_number',
							'tbl_companies_addresses.interior_number',
							'tbl_companies_addresses.colony_town',
							'tbl_companies_addresses.delegation_municipality',
							'tbl_companies_addresses.postal_code',
							'tbl_companies_addresses.between_streets',
							'tbl_companies_addresses.reference',
							'cat_countries.id_country',
							'cat_countries.name AS country',
							'cat_federal_entity.id_federal_entity',
							'cat_federal_entity.name AS federal_entity'
						)
				->where('tbl_companies_addresses.id_company_address',$id_site)
				->get();

	$sectorization = app('db')
						->table('trns_companies_sectors_norms')
						->select(
									'id_company_sector_norm',
									'id_company',
									'type_service_key',
									'type_service_name',
									'sector_type_key',
									'sector_type_name',
									'sector_name',
									'norm_key',
									'norm_name',
								)
						->where('id_company',$id_company)
						->get();

	$site_norms = app('db')
						->table('trns_sites_norms')
						->leftjoin('trns_companies_sectors_norms','trns_sites_norms.id_company_sector_norm','=','trns_companies_sectors_norms.id_company_sector_norm')
						->select(
									'trns_sites_norms.id_site_norm',
									'trns_sites_norms.id_site',
									'trns_sites_norms.id_company_sector_norm',
									'trns_companies_sectors_norms.type_service_key',
									'trns_companies_sectors_norms.sector_name',
									'trns_companies_sectors_norms.norm_key'
								)
						->where([['trns_sites_norms.status',1],['trns_sites_norms.id_site',$id_site]])
						->get();

	$site_factor_reduction = app('db')
								->table('trns_sites_factorsreductionenlargement')
								->select(
											'id_site_factor',
											'id_factor',
											// 'factor_type',
											'percentage',
											'all_management_systems'
										)
								->where([
											['status',1],
											['id_site',$id_site],
											['factor_type','reduction']
										])
								->get();

	$site_factor_enlargement = app('db')
								->table('trns_sites_factorsreductionenlargement')
								->select(
											'id_site_factor',
											'id_factor',
											// 'factor_type',
											'percentage',
											'all_management_systems'
										)
								->where([
											['status',1],
											['id_site',$id_site],
											['factor_type','enlargement']
										])
								->get();

	/*var_dump($site);
	var_dump($sectorization);
	var_dump( array( $site,$sectorization ) );
	exit();*/

	// return response()->json($sectorization);
	return response()->json(
								array(
									'site'						=> $site,
									'sectorization'				=> $sectorization,
									'site_norms'				=> $site_norms,
									'site_factor_reduction'		=> $site_factor_reduction,
									'site_factor_enlargement'	=> $site_factor_enlargement
								)
							);
});

/* Esta ruta API devuelve las normas de una compañia para agregarlas a un sitio  */
/* This API route returns the standards of a company to add to a site */
$router->post('api/v1/sectorization/company/norms/get', function(Illuminate\Http\Request $request) use ($router)
{
	$id 	= $request->input('id');

	$norms = app('db')
				->table('trns_companies_sectors_norms')
				->select(
							'id_company_sector_norm',
							'type_service_key',
							'type_service_name',
							'sector_type_key',
							'sector_type_name',
							'sector_name',
							'norm_key',
							'norm_name'
						)
				->where([
							['status',1],
							['id_company',$id]
						])
				->get();

	return response()->json($norms);
});

/* Esta ruta API devuelve los Typos de seervicios de una compañia */
/* This API route returns the types of services of a company */
$router->post('api/v1/sectorization/company/types_services/get', function(Illuminate\Http\Request $request) use ($router)
{
	$id 	= $request->input('id');

	$norms = app('db')
				->table('trns_companies_sectors_norms')
				->select(
							'id_type_service',
							'type_service_key',
							'type_service_name',
							// 'sector_type_key',
							// 'sector_type_name',
							// 'sector_name',
							// 'norm_key',
							// 'norm_name'
						)
				->distinct()
				->where([
							['status',1],
							['id_company',$id]
						])
				->get();

	return response()->json($norms);
});



/* Esta ruta API actualiza los datos basicos de una compañia */
/* This API route updates the basic data of a company */
$router->post('backend/companies/basic/update', function (Illuminate\Http\Request $request) use ($router) {

	$id_company 		= $request->input('cve_company');
	$id_type_company 	= $request->input('company_type');
	$id_type_person 	= $request->input('person_type');
	// $size 				= $request->input('size');
	$multisite			= $request->input('multi_site');
	$heat_label 		= $request->input('heat_label');
	$owner 				= $request->input('owner');
	$tradename 			= $request->input('tradename');
	$business_name 		= $request->input('business_name');
	$web_page 			= $request->input('web_page');

	$message 			= 'error|Error. Consult your administrator!';

	$edit = app('db')
				->table('tbl_companies')
				->where('id_company', $id_company)
				->update([
					'id_type_company'	=> $id_type_company,
					'id_type_person'	=> $id_type_person,
					'id_heat_label'		=> $heat_label,
					'multisite' 		=> $multisite,
					'id_owner' 			=> $owner,
					'tradename'			=> $tradename,
					'business_name'		=> $business_name,
					'web_page' 			=> $web_page,
					'modification_date'	=> date('Y-m-d H:i:s'),
					'modification_user'	=> $request->session()->get('user_id')
				]);

	if ($edit == 1) {

		$message = 'success|Register successfully updated!';
	}
	else {

		$message = 'error|Error updating registry!';
	}

	$request->session()->put('system_notification', $message);

	return redirect()->route('edit_company', ['id' => $id_company] );
});

/* Esta ruta API devuelve los grupos de sitios que tiene una compañia segun su actividad de cada sitio */
/* This API route returns the groups of sites that a company has according to its activity on each site */
$router->post('api/v1/companies/company/sites/groups/get', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= $request->input('id');
	$groups = app('db')
					->table('tbl_companies_addresses')
					->leftJoin(
								'cat_companies_siteactivities',
								'tbl_companies_addresses.id_company_siteactivity',
								'=',
								'cat_companies_siteactivities.id_company_siteactivity'
							)
					->select(
								'tbl_companies_addresses.id_company_siteactivity',
								'cat_companies_siteactivities.name'
							)
					->where([
								['tbl_companies_addresses.id_company', '=', $id],
								['tbl_companies_addresses.status', '=', 1]
							])
					->groupby('cat_companies_siteactivities.name')
					->get();

	return response()->json($groups);
});

/* Esta ruta API devuelve los sitios de un grupo fr sitios de una compañia por id_company_siteactivity */
/* This API route returns the sites of a group fr sites of a company by id_company_siteactivity */
$router->post('api/v1/companies/company/sites/group/get', function(Illuminate\Http\Request $request) use ($router) {

	$idC 	= $request->input('idC');
	$idG 	= $request->input('idG');
	$groups = app('db')
					->table('tbl_companies_addresses')
					->where([
								['tbl_companies_addresses.status', '=', 1],
								['tbl_companies_addresses.id_company', '=', $idC],
								['tbl_companies_addresses.id_company_siteactivity', '=', $idG]
							])
					->get();

	return response()->json($groups);
});

/* Esta ruta API devuelve los sitios de una compañia */
/* This API route returns the sites of a company */
/*$router->post('api/v1/companies/company/sites/get', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= $request->input('id');
	$sites = app('db')
					->table('tbl_companies_addresses')
					->leftJoin(
								'cat_companies_siteactivities',
								'tbl_companies_addresses.id_company_siteactivity',
								'=',
								'cat_companies_siteactivities.id_company_siteactivity'
							)
					->select(
								'tbl_companies_addresses.id_company_address',
								'tbl_companies_addresses.alias',
								'tbl_companies_addresses.id_company_siteactivity',
								'tbl_companies_addresses.repetitive_activity',
								'tbl_companies_addresses.total_repetitive_activity',
								'cat_companies_siteactivities.id_company_siteactivity',
								'cat_companies_siteactivities.name'
							)
					->where([
								['tbl_companies_addresses.id_company', '=', $id],
								['tbl_companies_addresses.status', '=', 1]
							])
					->orderby('tbl_companies_addresses.alias')
					->get();
	$groups = app('db')
					->table('tbl_companies_addresses')
					->leftJoin(
								'cat_companies_siteactivities',
								'tbl_companies_addresses.id_company_siteactivity',
								'=',
								'cat_companies_siteactivities.id_company_siteactivity'
							)
					->select(
								'tbl_companies_addresses.id_company_siteactivity',
								'cat_companies_siteactivities.name'
							)
					->where([
								['tbl_companies_addresses.id_company', '=', $id],
								['tbl_companies_addresses.status', '=', 1]
							])
					->groupby('cat_companies_siteactivities.name')
					->get();

	return response()->json( array('sites'=>$sites,'groups'=>$groups) );
});
*/

/* Esta ruta API devuelve los detalles del sitio de una compañia */
/* This API route returns the details of a company's site */
$router->post('api/v1/companies/company/site/details/get', function(Illuminate\Http\Request $request) use ($router) {

	$id_site 	= $request->input('id');
	$site 		= app('db')
						->table('tbl_companies_addresses')
						->select(
									// 'tbl_companies_addresses.id_company_address',
									// 'tbl_companies_addresses.id_company',
									'tbl_companies_addresses.alias',
									'tbl_companies_addresses.size',
									'tbl_companies_addresses.fixed_site',
									'tbl_companies_addresses.repetitive_activity',
									'tbl_companies_addresses.total_repetitive_activity',
									'tbl_companies_addresses.name_repetitive_activity',
								)
						->where('tbl_companies_addresses.id_company_address',$id_site)
						->get();

	$norms 		= app('db')
						->table('trns_sites_norms')
						->leftjoin(
									'trns_companies_sectors_norms',
										'trns_sites_norms.id_company_sector_norm', '=', 'trns_companies_sectors_norms.id_company_sector_norm'
								)
						->select(
									'trns_sites_norms.id_site_norm',
									'trns_sites_norms.id_site',
									'trns_sites_norms.id_company_sector_norm',
									'trns_companies_sectors_norms.type_service_key',
									'trns_companies_sectors_norms.sector_name',
									'trns_companies_sectors_norms.norm_key'
								)
						->where([['trns_sites_norms.status',1],['trns_sites_norms.id_site',$id_site]])
						->get();

	return response()->json( array('site'=>$site,'norms'=>$norms) );
});

/* Esta ruta API inserta un factor de reducción o ampliacion al sitio de una compañia */
/* This API route inserts a reduction or extension factor to a company site */
$router->post('api/v1/companies/company/sites/factor_reduction_enlargement/add', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= '0';
	$insert = app('db')
				->table('trns_sites_factorsreductionenlargement')
				->insert([
							'id_site'					=> $request->input('site'),
							'id_factor'					=> $request->input('factor_cve'),
							'factor_type'				=> $request->input('factor_type'),
							'percentage'				=> $request->input('factor_percent'),
							'all_management_systems'	=> $request->input('factor_name'),
							'creation_date'				=> date('Y-m-d H:i:s'),
							'creator_user'				=> $request->session()->get('user_id')
				]);

	$return = array('result'=>'error', 'message'=>'Error to save the record!');

	if ($insert == 1) {

		$id = app('db')->table('trns_sites_factorsreductionenlargement')->get()->last(); // Se recupera el id asignado a la nueva compañia
		$return = array('result'=>'success', 'message'=>'Record successfully saved!', 'id'=>$id->id_site_factor);
	}

	return response()->json($return);
});

/* Esta ruta API inserta las normas asignadas al sitio de una compañia */
/* This API route inserts the rules assigned to a company site */
$router->post('api/v1/companies/company/sites/norm/add', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= '0';
	$insert = app('db')
				->table('trns_sites_norms')
				->insert([
							'id_site'					=> $request->input('site'),
							'id_company_sector_norm'	=> $request->input('id_norm'),
							'creation_date'				=>	date('Y-m-d H:i:s'),
							'creator_user'				=>	$request->session()->get('user_id')
				]);

	$return = array('result'=>'error', 'message'=>'Error to save the record!');

	if ($insert == 1) {

		$id = app('db')->table('trns_sites_norms')->get()->last(); // Se recupera el id asignado a la nueva compañia
		$return = array('result'=>'success', 'message'=>'Record successfully saved!', 'id'=>$id->id_site_norm);
	}

	return response()->json($return);
});

/* Esta ruta API elimina una norma asignada al sitio de una compañia */
/* This API route removes a rule assigned to a company site */
$router->post('api/v1/companies/company/site/norms/delete', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= $request->input('id');
	$update = app('db')
					->table('trns_sites_norms')
					->where('id_site_norm',$id)
					->update([
						'status'			=>	0,
						'modification_date'	=>	date('Y-m-d H:i:s'),
						'modification_user'	=>	$request->session()->get('user_id')
					]);

	// $message = 'error|Error to delete the record!';
	$return = array('result'=>'error', 'message'=>'Error to delete the record!');

	if ($update == 1) {

		//$message = 'success|Registry deleted successfully!';
		$return = array('result'=>'success', 'message'=>'Registry deleted successfully!');
	}

	return response()->json($return);
});

/* Esta ruta API elimina un factor de reducción o ampliación asignado al sitio de una compañia */
/* This API route eliminates a reduction or extension factor assigned to a company site */
$router->post('api/v1/companies/company/site/factor_reduction_enlargement/delete', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= $request->input('id');
	$update = app('db')
					->table('trns_sites_factorsreductionenlargement')
					->where('id_site_factor',$id)
					->update([
						'status'			=>	0,
						'modification_date'	=>	date('Y-m-d H:i:s'),
						'modification_user'	=>	$request->session()->get('user_id')
					]);

	// $message = 'error|Error to delete the record!';
	$return = array('result'=>'error', 'message'=>'Error to delete the record!');

	if ($update == 1) {

		//$message = 'success|Registry deleted successfully!';
		$return = array('result'=>'success', 'message'=>'Registry deleted successfully!');
	}

	return response()->json($return);
});







/********* Esta funcion se pasa a sectorizacion.php  (BORRAR esta función)*********/
/********* Se cambia la ruta por: 'api/v1/sectors/type_service' *********/
$router->post('api/v1/companies/sectors/type_sector', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= $request->input('id');
	$sectors = app('db')
					->table('cat_sectors_details')
					->select('id_sector_details', 'description')
					->where([
								['id_sector', '=', $id],
								['status', '=', 1]
							])
					->orderby('description')
					->get();

	return response()->json($sectors);
});
/********* /.Esta funcion se pasa a sectorizacion.php  (BORRAR esta función)*********/

/********* Esta funcion se pasa a sectorizacion.php  (BORRAR esta función)*********/
/********* Se cambia la ruta por: 'api/v1/sectors/type_service/norms' *********/
$router->post('api/v1/companies/sectors/sector_norms', function(Illuminate\Http\Request $request) use ($router) {

	$id 	= $request->input('id');
	$sector_norms = app('db')
					->table('trns_sectors_norms')
					->leftJoin('cat_sectors_details', 'trns_sectors_norms.id_sector_details', '=', 'cat_sectors_details.id_sector_details')
					->leftJoin('cat_norms', 'cat_norms.id_norm', '=', 'trns_sectors_norms.id_norm')
					->select(
								'trns_sectors_norms.id_sectors_norms',
								'trns_sectors_norms.id_sector_details',
								'trns_sectors_norms.id_norm',
								'cat_sectors_details.description',
								'cat_sectors_details.description',
								'cat_norms.key',
								'cat_norms.name',
							)
					->where([
								['trns_sectors_norms.status','=',1],
								['trns_sectors_norms.id_sector_details','=',$id]
							])
            		->get();

	return response()->json($sector_norms);
});
/********* /.Esta funcion se pasa a sectorizacion.php  (BORRAR esta función)*********/