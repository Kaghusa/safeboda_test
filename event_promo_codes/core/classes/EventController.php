<?php
class EventController
{   
	

	public static function checkEventStatus($event_code)
	{
		$EventTable = new \Event();
		$EventTable->selectQuery("SELECT status as event_status  FROM event WHERE event_code =? ORDER BY id DESC LIMIT 1", array($event_code));
		if ($EventTable->count())
		 	return $EventTable->first()->event_status;
		return "N";
	}

	public static function getEventDataByCode($event_code)
	{
		$EventTable = new \Event();
		$EventTable->selectQuery("SELECT  * from event where event_code=? ", array($event_code));
		if ($EventTable->count())
		 	return $EventTable->first();
		return false;
	}
}