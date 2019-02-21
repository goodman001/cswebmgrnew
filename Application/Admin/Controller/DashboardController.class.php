<?php
namespace Admin\Controller;
use Think\Controller;
class DashboardController extends CommonController {
    public function index()
    {
      
        /* cal total complete income */

        /**/
        $year = date("Y");
        $res = [];
        $res = getAllData();
        $cydata = [];
        $cydata = getYearData($year);
        $this->assign('revenues',$res[0]);
        $this->assign('salary',$res[1]);
        $this->assign('profit',$res[2]);
        $this->assign('revenuesarr',$res[3]);
        $this->assign('ordernum',$res[4]);


        $this->assign('ongoingrevenues',$res[5]);
        $this->assign('ongoingsalary',$res[6]);
        $this->assign('ongoingprofit',$res[7]);
        $this->assign('ongoingrevenuesarr',$res[8]);
        $this->assign('profitavg',$res[9]);
        $this->assign('ongoingunpaid',$res[10]);
        $this->assign('ongoingdoing',$res[11]);
        $this->assign('ongoingunset',$res[12]);
		$this->assign('unpaidsalary',$res[13]);

		
        $this->assign('cydata',$cydata);//current day data info

        //print_r($res);

        $fromdate = date("Y-m-d");
        $this->assign('today',$fromdate);
        $this->assign('todaymonth',date("Y-m"));
        $this->assign('todayyear',$year);
        /* display */

        /*
        month show
        */



        $this->display(T('admin/index'));
    }

    public function getDayData(){
      $daydata = I('post.daytime','','htmlspecialchars');//
      $datas = getDayData($daydata);
      $this->ajaxReturn($datas);
    }


    public function getMonthData(){
        $daydata = I('post.daytime','','htmlspecialchars');//
        $res = getMonthData($daydata);
        $this->ajaxReturn($res);
    }



    public function getYearData(){
      /*year show*/
      $year =  I('post.daytime','','htmlspecialchars');//
      //$year = "2018";
      $res = [];
      $res = getYearData($year);
      $this->ajaxReturn($res);
    }
    public function showDataMoneyAnalysis(){
        $res = [];
        $res = getAllData();
        $this->assign('revenues',$res[0]);
        $this->assign('salary',$res[1]);
        $this->assign('profit',$res[2]);
        $this->assign('revenuesarr',$res[3]);
        $this->assign('ordernum',$res[4]);
        $this->assign('ongoingrevenues',$res[5]);
        $this->assign('ongoingsalary',$res[6]);
        $this->assign('ongoingprofit',$res[7]);
        $this->assign('ongoingrevenuesarr',$res[8]);
        $this->assign('profitavg',$res[9]);
        $this->assign('ongoingunpaid',$res[10]);
        $this->assign('ongoingdoing',$res[11]);
        $this->assign('ongoingunset',$res[12]);

        $DATEYEAR = date("Y");
        /*[createyear] => [salarysum] => 0 [revenuesum] => 0 [moneyinfo] => [profitsum] => 0 [ordernum] => 0 [profitavg] => 0 [datas] => Array ( ) )*/
        $salarysum = 0;
        $revenuesum = 0;
        $profitsum  = 0;
        $ordernum = 0;
        $datas = [];
        for($i=C(DATEORIYEAR);$i<=$DATEYEAR;$i++){
            $cydata = [];
            $cydata = getYearData($i);
            $salarysum = $salarysum + $cydata["salarysum"];
            $revenuesum = $revenuesum + $cydata["revenuesum"];
            $profitsum = $profitsum + $cydata["profitsum"];
            $ordernum = $ordernum + $cydata["ordernum"];
            array_push($datas,$cydata);
        }
        $cydata["createyear"] = "Total" ;
        $cydata["salarysum"] = $salarysum;
        $cydata["revenuesum"] = $revenuesum;
        $cydata["profitsum"] = $profitsum;
        $cydata["ordernum"] = $ordernum;
        array_push($datas,$cydata);
        //print_r($datas);
        $this->assign('datas',$datas);//current day data info

        //print_r($cydata);

        $fromdate = date("Y-m-d");
        $this->assign('today',$fromdate);
        $this->assign('todaymonth',date("Y-m"));
        $this->assign('todayyear',$year);
        $this->display(T('admin/dashbord_data_analysis'));

    }
    /* one year */
    public function getDayToDay(){

      $fd = I('post.fromdate','','htmlspecialchars');//
      $td = I('post.todate','','htmlspecialchars');//
      $DATEYEAR = date("Y",time());

      $res = [];
      for($i=C(DATEORIYEAR);$i<=$DATEYEAR;$i++){
        $fromdate = $i."-".$fd;
        $todate = $i."-".$td;
        $tmp = getDayToDay($fromdate,$todate);
        if(!empty($tmp['values'])){
             array_push($res,$tmp);
        }

      }
      $this->ajaxReturn($res);
    }
    /*get days of potin month*/
    public function getEachMonth(){
      $m = I('post.month','','htmlspecialchars');//
      //$m = "02";
      $res = [];
      $res0 = [];
      //echo C(DATEORIYEAR);
      $DATEYEAR = date("Y",time());
      //echo $DATEYEAR;
      for($i=C(DATEORIYEAR);$i<=$DATEYEAR;$i++){
        //echo $i;
        $fromdate = $i."-".$m."-01";
        $todate = $i."-".$m."-31";
        $res0 = getDayToDayYears($fromdate,$todate);
        if(empty($res0)){
          $res0 = [];
        }
        $res = array_merge($res,$res0);
        //print_r($res0);

      }
      $i = 0;
      for($i = 0; $i<count($res);$i++){
        //echo ($k["name"]);
        //echo (count($k["values"]));
        //print_r($res[$i]["values"]);
        if(count($res[$i]["values"]) == 0){
          unset($res[$i]);
        }
        //echo "<br>";
      }
      //print_r($res);
      $this->ajaxReturn($res);
    }
    public function getMonths(){
        $fy = C(DATEORIYEAR);
        $ty = date("Y",time());
        $res = getMonthsData($fy,$ty,0);
        $this->ajaxReturn($res);
    }
	public function getMonthsProfitPerDay(){
        $fy = C(DATEORIYEAR);
        $ty = date("Y",time());
        $res = getMonthsData($fy,$ty,2);
        $this->ajaxReturn($res);
    }
    public function getQdatas(){
        $fy = C(DATEORIYEAR);
        $ty = date("Y",time());
        /*
        q1: 1-31 -3-31
        q2: 4-1  6-30
        q3  7-1  9-30
        q4  10-1 12-31
        */

        /*get Q1*/
        $res1 = getQData($fy,$ty,"Q1",0);
        $res2 = getQData($fy,$ty,"Q2",0);
        $res3 = getQData($fy,$ty,"Q3",0);
        $res4 = getQData($fy,$ty,"Q4",0);
        $res = [];
        array_push($res,$res1,$res2,$res3,$res4);
        //print_r($res);
        $this->ajaxReturn($res);
    }
	public function getYearProfitPercent(){
		$fy = $ty = I('post.year','','htmlspecialchars');//;
		$res = getMonthsData($fy,$ty,0);
		$dataset = [];
		foreach($res as $k=>$v){
			//$room = [];
			//echo $v;
			$room["label"] =  $v["month"];
			$room["count"] =  $v[$fy];
			//$room["profit"] =  $year_profitarray[$v["label"]];
			array_push($dataset,$room);
		}
		$this->ajaxReturn($dataset);
	}
	public function getWeekData(){
		//$fd = I('post.fromdate','','htmlspecialchars');//
		//$td = I('post.todate','','htmlspecialchars');//
		$fd = "2018-01-01";//
		$td = "2019-12-31";//
		if(date('w') == 0){
			$td = date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600));;
		}else{
			$td = date('Y-m-d', strtotime('-1 sunday', time()));
		}
		//echo $td;
		//date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算
		$res = [];
		$res = getWeekDataOrder($fd,$td);
		$this->ajaxReturn($res);
	}
}
