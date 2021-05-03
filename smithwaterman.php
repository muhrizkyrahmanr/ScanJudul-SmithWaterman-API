<?php
include 'arraylist.php';

class SmithWaterman
{
	private $s = 2;
	private $ss = -1;
	private $w = -1;
	private  $ob1;
	private $obxx;
	private $obx;

	private $arhsl1;
	private $arhsl2;
	private $hsll;
	private $gaps; 

	public $hasill=0;
    private $tempt=0;

    private $kata1;
    private $kata2;

    private $ar1;
    private $ar2;
    private $hsl;

    public $persen = 0;


	public function __construct($judul1,$judul2)
	{
		$this->ob1 = new ArrayList();
		$this->obxx = new ArrayList();
		$this->obx = new ArrayList($this->obxx);

		$this->arhsl1 = new ArrayList();
		$this->arhsl2 = new ArrayList();
		$this->hsll = new ArrayList();
		$this->gaps = new ArrayList();

        $this->scanSmithWaterman($judul1,$judul2);

	}

	function cekKesamaan($c,$b){
		$temp = 0;
		$sss = new ArrayList();
		$obx = $this->obx;
		$sss = $obx->get($c-1);
		$temp = $sss->get($b-1);
		$cek1=$temp+$this->s;
		if($cek1==0){
          $cek1=-1;
      	}
        return $cek1;
	}

	function cekTidakSama($c,$b){
		$sss = new ArrayList();
		$obx = $this->obx;
		$sss = $obx->get($c-1);
		$temp1 = $sss->get($b-1)+$this->ss;
		$sss = $obx->get($c);
		$temp2 = $sss->get($b-1)+$this->w;
		$sss = $obx->get($c-1);
		$temp3 = $sss->get($b)+$this->w;
		$cek = $temp1>$temp2 && $temp1>$temp3 ? $temp1:$temp2>$temp3?$temp2:$temp3;
		if($cek==0){
          $cek=-1;
      	}
        return $cek;
	}


    function char_at($str, $pos)
    {
        return $str{$pos};
    }

	function becek($in1,$in2){
		$x = new ArrayList();
		$obx = $this->obx;
		$x = $obx->get($in1);
		$nil = $x->get($in2);
        $ref1 = $x->get($in2-1);//samping
        $x= $obx->get($in1-1);
        $ref2 = $x->get($in2);//atas
        $x = $obx->get($in1-1);
        $ref3 = $x->get($in2-1);//diagonal
        $gap;
        if($ref1>$ref2 && $ref1>$ref3){
            $this->ar1=$in1;
            $this->ar2=$in2-1;
            $this->gap=1;
        }else if ($ref2>$ref3 && $ref2>=$ref1){
            $this->ar1=$in1-1;
            $this->ar2=$in2;
            $this->gap=2;
        }else if($ref1==$ref2 && $ref1==$ref3 && $ref2==$ref3){
            $this->ar1=$in1-1;
            $this->ar2=$in2-1;
            $this->gap=0;
        }else{
            $this->ar1=$in1-1;
            $this->ar2=$in2-1;
            $this->gap=0;
        }
        $this->hsl = $obx->get($this->ar1)->get($this->ar2);
        if($this->hsl != 0){
        	$arhsl1 = $this->arhsl1;
        	$arhsl2 = $this->arhsl2;
        	$hsll = $this->hsll;
        	$gaps = $this->gaps;

            $arhsl1->add($this->char_at($this->kata1,$this->ar1-1));
            $arhsl2->add($this->char_at($this->kata2,$this->ar2-1));
            $hsll->add($this->hsl);
            $gaps->add($this->gap);
    	}  
	}


    function totalHasil($a,$b,$c){
        if($a == $b && $c==0){
            $this->hasill+=2;
            $this->tempt+=1;
        }else {
            $this->hasill+=(-1);
        }
    }


    function preProses($string){
        $result = preg_replace("/[^a-zA-Z]/", "", strtolower($string));
        return $result;
    }

    function scanSmithWaterman($judul1,$judul2){
        $judul1 = $this->preProses($judul1);
        $judul2 = $this->preProses($judul2);
        $this->kata1 = $judul1;
        $this->kata2 = $judul2;

        $this->ob1 = new ArrayList();
        for ($j = 0; $j <= strlen($judul2); $j++) {
            $this->ob1->add(0);
        }
        $this->obx->add($this->ob1);

        for ($i = 1; $i <= strlen($judul1); $i++) {
            $this->ob1 = new ArrayList();
            $this->ob1->add(0);
            $this->obx->add($this->ob1);
                for ($j = 1; $j <= strlen($judul2); $j++) {
                        if($this->char_at($judul1,$i-1) == $this->char_at($judul2,$j-1)){
                            $n = $this->cekKesamaan($i, $j);
                            $this->ob1->add($n);
                        } else {
                            $n = $this->cekTidakSama($i, $j);
                            $this->ob1->add($n);
                        }
                }
        }
        // echo "=====================================<br>";
        // echo "Kata 1 = ".$judul1."<br>";
        // echo "Kata 2 = ".$judul2."<br>";
        // for ($i = 0; $i < $this->obx->size(); $i++) {
        //         for ($j = 0; $j < $this->ob1->size(); $j++) {
        //             echo $this->obx->get($i)->get($j)." ";  
        //         }
        //         echo "<br>";
        // }

        $lastindex = $this->obx->size()-1;
        $x = new ArrayList();
        $x = $this->obx->get($lastindex);
        $lastchild = $x->size()-1;
        $this->hsl = $x->get($lastchild);
        $this->ar1 = $lastindex;
        $this->ar2 = $lastchild;
        $this->arhsl1->add($this->char_at($this->kata1,$lastindex-1));
        $this->arhsl2->add($this->char_at($this->kata2,$lastchild-1));
        $this->hsll->add($this->hsl);
        $this->gaps->add(0);

        while($this->hsl != 0){
            $this->becek($this->ar1, $this->ar2);
        }

        $temp=0;
        for ($i = 0; $i < $this->arhsl1->size(); $i++) {
            $hrf2 = $this->gaps->get($i)==1?'-':$this->arhsl2->get($i);
            $hrf1 = $this->gaps->get($i)==1?'-':$this->arhsl1->get($i);
            // echo $hrf1." : ".$hrf2." = ".$this->hsll->get($i)."<br>";
            $this->totalHasil($this->arhsl1->get($i), $this->arhsl2->get($i),$this->gaps->get($i));
        }

        // echo "Hasil = ".$this->hasill."<br>";
        // echo "Skor Kemiripan = ".$this->hasill."<br>";

        $p1 = strlen($judul1);
        $p2 = strlen($judul2);
        $hs = ($p1+$p2)-($this->hasill);
        $t = $this->tempt;
        $hs1 = ($hs/($p1+$p2))*100;
        $x1=($t/$p1);
        $x2=($t/$p2);
        $this->persen =(($x1+$x2)/2)*100;

        // echo "Presentasi Kemiripan = ".$persen." % <br>";
        // echo "=====================================<br>";
    }

    public function getScore(){
        return $this->hasill;
    }

    public function getPresen(){
        return $this->persen;
    }

}
?>