var d=new Date().getTime();
var a=document.URL;
var c=window.name;
N_max = 20 //максимальное число рефрешов страницы подряд за время TimeMin
TimeMin = 60000;//Время действия условия. 1 минут
var d0=d;
var i=1;
if(c!=''){var arr=c.split(",");
  if(arr.length==4 && arr[2]==a){i=parseInt(arr[3])+1;
//alert(i)
  } 
   else window.name=(d+","+d0+","+a+","+1);
}  else window.name=(d+","+d0+","+a+","+1);
if(i>1){d0=parseInt(window.name.split(",")[1])}
if(isNaN(i)||isNaN(d0)){i=1;d0=d;}
//alert(window.name.split(",")[1])
if( i >=N_max){
    if(d-d0<= (TimeMin/N_max)*i){alert('Хорош обновлять страницу!!!');window.name='';window.location="http://localhost/lazysmart/wait.php"}
    window.name='';d0=d;i=1;
}
window.name=(d+","+d0+","+a+","+i);
//alert(window.name);