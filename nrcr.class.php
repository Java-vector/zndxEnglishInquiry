 <?php
		//引入simple_html_dom类库
		require_once('./lib/simple_html_dom/simple_html_dom.php');
	/**
	* 查询英语四六级类库
	*/
	class NRCR  
	{
		public $student_id;//用户的学号
		public $student_name;//用户姓名
		public $select;
		// 初始化构造函数
		public function __construct($STUDENT_ID,$STUDENT_NAME,$SELECT)
		{
			$this->student_id = $STUDENT_ID;
			$this->student_name = $STUDENT_NAME;
			$this->select = $SELECT;
		}
	
		/**
		 * 从学信网查询最近一次四六级英语考试成绩www.chsi.com.cn/cet/
		 * 查询英语四六级成绩
		 * @param , $zkzh 准考证号
		 * @param , $xm 姓名
		 * return返回获得的数据
		 */
		public function InquiryEng($zkzh,$xm){
				//拼接url
				$url = 'http://www.chsi.com.cn/cet/query?'.'zkzh='.$zkzh.'&&xm='.$xm;
				//初始化curl类库
				$ch = curl_init();
				//设置选项，包括URL
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER,1);
				$header = array('header' =>'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0' );
				curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
				curl_setopt($ch,CURLOPT_REFERER,'http://www.chsi.com.cn/cet/');
				//执行并获取HTML文档内容
				$output = curl_exec($ch);
				//释放curl句柄
				curl_close($ch);
				$html = new simple_html_dom();
				//加载带解析html文档
				$html->load($output);
                $tr=$html->find('tr');
                //保存最后查询的数据
                $data = [];
                $i = 0;
                //循环保存所需数据
                foreach ($tr as $key => $value) {
                	if ($key<1 or $key == 4 or $key == 10 or $key == 11 or $key ==12) {
                		continue;
                	}else{
                		if ($key == 7 or $key == 8 or $key == 9) {
                			$data[0]["grade".$i] = trim($value->children(2)->plaintext);//获取子节点的文本
                		}else{
                			// $index = trim($value->children(0)->plaintext);
                			$data[0]["grade".$i] = trim($value->children(1)->plaintext);
                		}
                	}
                	$i++;
                }
                return $data;
		}
		/**
		 * 访问学校教务网查询四六级成绩
		 * @param  $student_id   学号
		 * @param  $student_name 姓名
		 * @param  $select       是否查询所有成绩
		 */
		public function InquiryId($student_id,$student_name,$select){
			//学校教务系统查询时的网页编码为gb2312故应先将汉字转换成对应的gb2312编码
            $url = 'http://202.197.61.241/engfen.asp?xm='.iconv('utf-8','gb2312', $student_name).'&sfzh=&zkzh=&xh='.$student_id;
            $post_data = array('xm' =>$student_name,'xh'=>$student_id,'zkzh'=>'','sfzh'=>'','Submit'=>'确定');
            //初始化curl库函数
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER,1);
			$header = array('Mozilla/5.0 (Windows NT 10.0; …) Gecko/20100101 Firefox/57.0');
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
			curl_setopt($ch,CURLOPT_REFERER,'http://202.197.61.241/searchenfen.asp');
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 

			$output = curl_exec($ch);
			//关闭
			curl_close($ch);
			//采用正则表达式对html文档进行匹配
			preg_match_all('/<td(.*)>\d{15}<\/td>/', $output, $result);//获取所有准考证号
			preg_match_all('/<td(.*)>\d{3}<\/td>/',$output,$result_cj);//获取所有成绩
			preg_match_all('/\d{15}/',json_encode($result),$result_data);//去除<td>标签
			preg_match_all('/\d{3}/',json_encode($result_cj),$cj_data);
			// var_dump($result_data[0][count($result_data)-1]);
			for ($i=0; $i <count($result[0]) ; $i++) { 
				$result_f[$i]['zkzh'] = $result_data[0][$i];
				if($i>=count($cj_data[0])){
					$result_f[$i]['cj'] = '0';
				}else{
					$result_f[$i]['cj'] = $cj_data[0][$i];
				}
				$result_f[$i]['select'] = '1';
			}
			if ($select) {
				$result_final = $result_f;//所有四六级成绩
			}elseif (count($result_data[0])!= 0) {
				$result_final = $result_data[0][count($result_data[0])-1];//2017年四六级考试准考证号
			}else{
				$result_final = null;
			}
			return $result_final;
		}
	}
?>