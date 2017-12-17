* 通过学信网查询2017年上半年的四六级考试成绩及获取四六级历史考试成绩
* 1、类属性：
    * student_id:学生的学号；
	* student_name：学生姓名;
	* select:布尔值,判断是否查询2017上半年的四六级成绩,true查询上半年成绩，否则查询所有历史成绩
* 2、公有方法：
	* 1、InquiryEng方法：查询2017年上半年成绩，传入准考证号，姓名
	* 2、InquiryId方法：查询历史四六级成绩，传入学号，姓名，布尔值

* 3、使用方法：  
	* 通过require_once('nrcr.class.php')引入  
	* 实例化对象：$nrcr = new NRCR($student_id,$student_name,$select);
