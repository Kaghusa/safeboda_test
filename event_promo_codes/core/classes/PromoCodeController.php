<?php
class PromoCodeController
{  
    public static function Add($_DATA){
        $PromoCodeTable = new \PromoCode();
            try
            {
                $PromoCodeTable->insert($_DATA);
                return true;
            }
            catch(Exception $e){
               return false;
            }
    
    }

 

    public static function updateEntries($fields,$conditions){
        $PromoCodeTable = new \PromoCode(); 

        try{
            $PromoCodeTable->updateMultiple($fields,$conditions);
            return true;

        }catch(Exception $e){
           return false;
        }
    }
    public static  function generatePromoCode($length){
      $coupon = "SB".substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',$length-2)),0,$length-2);
      return $coupon;
    }

    public static function checkPromoCodeStatus($promo_code)
	{
		$PromoCodeTable = new \PromoCode();
		$PromoCodeTable->selectQuery("SELECT status as promo_status  FROM event_promo_code WHERE promo_code =? ORDER BY id DESC LIMIT 1", array($promo_code));
		if ($PromoCodeTable->count())
		 	return $PromoCodeTable->first()->promo_status;
		return "N";
	}

	public static function getPromoCodeDataByCode($promo_code)
	{
		$PromoCodeTable = new \PromoCode();
		$PromoCodeTable->selectQuery("SELECT  * from event_promo_code where promo_code=? ", array($promo_code));
		if ($PromoCodeTable->count())
		 	return $PromoCodeTable->first();
		return false;
  }
  

  public static function getAllPromoCodes()
	{
		$PromoCodeTable = new \PromoCode();
		$PromoCodeTable->selectQuery("SELECT  * from event_promo_code order by id desc ");
		if ($PromoCodeTable->count())
		 	return $PromoCodeTable->data();
		return false;
  }
  

  public static function getActivePromoCodes()
	{
		$PromoCodeTable = new \PromoCode();
		$PromoCodeTable->selectQuery("SELECT  * from event_promo_code where status=? ", array('Y') );
		if ($PromoCodeTable->count())
		 	return $PromoCodeTable->data();
		return false;
	}
   
    


   


  
       
}