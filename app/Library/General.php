<?php namespace App\Library {
    class General{

        public static function canon_encode($id=''){
            $code = 'Malia' . $id . 'Malia';
            $code = base64_encode($code);
            return $code;
        }
    
        public static function canon_decode($code = ''){
            $code = base64_decode($code);
            return $code=substr($code, 5, 1);
        }
		
    }
}