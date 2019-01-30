<?php
namespace Admin\Controller;
use Think\Controller;
class DashboardController extends CommonController {
    public function index()
    {
      $uid = cookie('admin_uid');
      $username = cookie('admin_username');

      $data['uid'] = $uid;
      $Model = M('admin');
      $content = $Model->where($data)->find();
      if(!empty($content) )//exist
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

        $this->assign('cydata',$cydata);//current day data info

        //print_r($cydata);

        $fromdate = date("Y-m-d");
        $this->assign('today',$fromdate);
        $this->assign('todaymonth',date("Y-m"));
        $this->assign('todayyear',$year);
        /* display */

        /*
        month show
        */



        $this->display(T('admin/index'));
      }else
      {
      	$this->error(C('LOGIN_ERROR'), U('Login/index'),3);
      }
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
        $res = getMonthsData($fy,$ty);
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
        $res1 = getQData($fy,$ty,"Q1");
        $res2 = getQData($fy,$ty,"Q2");
        $res3 = getQData($fy,$ty,"Q3");
        $res4 = getQData($fy,$ty,"Q4");
        $res = [];
        array_push($res,$res1,$res2,$res3,$res4);
        //print_r($res);
        $this->ajaxReturn($res);
    }
}
