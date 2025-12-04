DROP TABLE IF EXISTS cart;

CREATE TABLE `cart` (
  `cart_id` char(14) NOT NULL,
  `content_code` varchar(50) DEFAULT NULL,
  `content_options` varchar(100) DEFAULT NULL,
  `content_amount` int(100) NOT NULL DEFAULT 1,
  `user_id` char(15) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO cart VALUES("20221127104017","221119-005","#76a2f9 / free","1","hyun");
INSERT INTO cart VALUES("20221128052924","221124-009","#45d3ca / free","1","ga");
INSERT INTO cart VALUES("20221208044838","221119-002","#257a1f / free","2","song");
INSERT INTO cart VALUES("20221208045922","221124-009","#781212 / free","2","join");
INSERT INTO cart VALUES("20251120053122","221208-001","oooo","1","rlatngla");



DROP TABLE IF EXISTS contents;

CREATE TABLE `contents` (
  `content_code` varchar(32) NOT NULL,
  `content_img` varchar(500) NOT NULL,
  `deliv_today` char(1) NOT NULL,
  `content_name` varchar(50) NOT NULL,
  `discount_rate` decimal(3,0) NOT NULL DEFAULT 0,
  `content_cost` int(11) NOT NULL,
  `content_price` int(11) NOT NULL,
  `content_color1` varchar(30) DEFAULT NULL,
  `content_color2` varchar(30) DEFAULT NULL,
  `content_color3` varchar(30) DEFAULT NULL,
  `content_color4` varchar(30) DEFAULT NULL,
  `category_large` varchar(50) DEFAULT NULL,
  `category_small` varchar(50) DEFAULT NULL,
  `content_img1` varchar(500) DEFAULT NULL,
  `content_img2` varchar(500) DEFAULT NULL,
  `content_img3` varchar(500) DEFAULT NULL,
  `content_img4` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`content_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO contents VALUES("MK-20251126-0001","img/organized_ascii/health/MK-20251126-0001.png","N","그릴드 닭가슴살 포케","0","6000","9300","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0002","img/organized_ascii/health/MK-20251126-0002.png","N","누룽지 삼계탕","0","11500","17800","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0003","img/organized_ascii/health/MK-20251126-0003.png","N","능이 오리백숙","0","14300","22000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0004","img/organized_ascii/health/MK-20251126-0004.png","N","닭가슴살 도시락","0","5200","8000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0005","img/organized_ascii/health/MK-20251126-0005.png","N","닭가슴살","0","10400","16100","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0006","img/organized_ascii/health/MK-20251126-0006.png","N","목살 스테이크 샐러드","0","13300","20600","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0007","img/organized_ascii/health/MK-20251126-0007.png","N","보양식 해신탕","0","7600","11800","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0008","img/organized_ascii/health/MK-20251126-0008.png","N","샐러드 마녀 비프포케 샐러드","0","7800","12000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0009","img/organized_ascii/health/MK-20251126-0009.png","N","세끼판다 단호박 샐러드","0","15200","23400","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0010","img/organized_ascii/health/MK-20251126-0010.png","N","쉐이크 비프 포케 샐러드","0","8300","12900","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0011","img/organized_ascii/health/MK-20251126-0011.png","N","쉐이크 쉬림프 포케 샐러드","0","12600","19500","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0012","img/organized_ascii/health/MK-20251126-0012.png","N","오븐 닭가슴살","0","9200","14300","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0013","img/organized_ascii/health/MK-20251126-0013.png","N","월남쌈","0","15100","23300","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0014","img/organized_ascii/health/MK-20251126-0014.png","N","전복죽","0","8300","12800","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0015","img/organized_ascii/health/MK-20251126-0015.png","N","추어탕","0","16100","24900","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0016","img/organized_ascii/other/MK-20251126-0016.png","N","건어물 5종세트","0","4800","7400","","","","","기타","","","","","");
INSERT INTO contents VALUES("MK-20251126-0017","img/organized_ascii/other/MK-20251126-0017.png","N","슈크림 붕어빵","0","4000","6300","","","","","기타","","","","","");
INSERT INTO contents VALUES("MK-20251126-0018","img/organized_ascii/western/MK-20251126-0018.png","N","가리비 바질파스타","0","19100","29500","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0019","img/organized_ascii/western/MK-20251126-0019.png","N","갈릭비프스테이크","0","19600","30200","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0020","img/organized_ascii/western/MK-20251126-0020.png","N","감바스","0","21300","32800","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0021","img/organized_ascii/western/MK-20251126-0021.png","N","고르곤졸라피자","0","8500","13200","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0022","img/organized_ascii/western/MK-20251126-0022.png","N","매운 크림 리조또","0","15200","23400","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0023","img/organized_ascii/western/MK-20251126-0023.png","N","매운 크림 파스타","0","14600","22600","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0024","img/organized_ascii/western/MK-20251126-0024.png","N","묵은지 들기름 파스타","0","19400","29900","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0025","img/organized_ascii/western/MK-20251126-0025.png","N","베이컨토마토파스타","0","11900","18400","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0026","img/organized_ascii/western/MK-20251126-0026.png","N","봉골레 파스타","0","14100","21700","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0027","img/organized_ascii/western/MK-20251126-0027.png","N","빕스 부채살 찹스테이크","0","15600","24100","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0028","img/organized_ascii/western/MK-20251126-0028.png","N","빕스 클래식 스테이크","0","15000","23200","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0029","img/organized_ascii/western/MK-20251126-0029.png","N","새우명란오일파스타","0","20400","31500","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0030","img/organized_ascii/western/MK-20251126-0030.png","N","새우봉골레 파스타","0","16800","25900","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0031","img/organized_ascii/western/MK-20251126-0031.png","N","생크림 새우 리조또","0","21100","32600","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0032","img/organized_ascii/western/MK-20251126-0032.png","N","생크림 새우 파스타","0","11900","18400","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0033","img/organized_ascii/western/MK-20251126-0033.png","N","알렌 뼈등심스테이크&페퍼콘소스","0","22000","33900","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0034","img/organized_ascii/western/MK-20251126-0034.png","N","알렌 스피니치페스토 에그생면파스타","0","18700","28900","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0035","img/organized_ascii/western/MK-20251126-0035.png","N","알렌 아라비아따 에그생면파스타","0","10700","16500","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0036","img/organized_ascii/western/MK-20251126-0036.png","N","알리오 올리오 파스타","0","13500","20900","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0037","img/organized_ascii/western/MK-20251126-0037.png","N","차돌박이 마늘쫑 오일파스타","0","10700","16600","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0038","img/organized_ascii/western/MK-20251126-0038.png","N","찹스테이크","0","15600","24000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0039","img/organized_ascii/western/MK-20251126-0039.png","N","트러플크림 뇨끼","0","15200","23400","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0040","img/organized_ascii/western/MK-20251126-0040.png","N","푸타네스카 파스타","0","9500","14700","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0041","img/organized_ascii/western/MK-20251126-0041.png","N","해산물 토마토 파스타","0","15400","23700","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0042","img/organized_ascii/western/MK-20251126-0042.png","N","화이트 라구 파스타","0","8600","13300","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0043","img/organized_ascii/japanese/MK-20251126-0043.png","N","규동 덮밥","0","22300","34400","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0044","img/organized_ascii/japanese/MK-20251126-0044.png","N","김재현 홈마카세 스시 밀키트","0","20500","31600","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0045","img/organized_ascii/japanese/MK-20251126-0045.png","N","돈코츠 라멘","0","17300","26700","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0046","img/organized_ascii/japanese/MK-20251126-0046.png","N","등심돈까스","0","12000","18500","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0047","img/organized_ascii/japanese/MK-20251126-0047.png","N","명란구이","0","19100","29400","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0048","img/organized_ascii/japanese/MK-20251126-0048.png","N","명란크림소바","0","24600","37900","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0049","img/organized_ascii/japanese/MK-20251126-0049.png","N","모츠나베","0","24900","38400","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0050","img/organized_ascii/japanese/MK-20251126-0050.png","N","문어가라아게","0","11400","17600","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0051","img/organized_ascii/japanese/MK-20251126-0051.png","N","밀푀유나베","0","22600","34900","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0052","img/organized_ascii/japanese/MK-20251126-0052.png","N","바질 고르곤졸라 뇨끼","0","23200","35700","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0053","img/organized_ascii/japanese/MK-20251126-0053.png","N","베이컨 야끼소바","0","20100","31000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0054","img/organized_ascii/japanese/MK-20251126-0054.png","N","사누끼우동면","0","18800","29000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0055","img/organized_ascii/japanese/MK-20251126-0055.png","N","삿포로생라멘면","0","16700","25800","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0056","img/organized_ascii/japanese/MK-20251126-0056.png","N","소고기 규동 밀키트","0","11600","17900","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0057","img/organized_ascii/japanese/MK-20251126-0057.png","N","소고기 스키야키","0","15500","23900","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0058","img/organized_ascii/japanese/MK-20251126-0058.png","N","소고기 야끼소바","0","16100","24900","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0059","img/organized_ascii/japanese/MK-20251126-0059.png","N","야끼찌꾸와","0","16700","25700","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0060","img/organized_ascii/japanese/MK-20251126-0060.png","N","야키토리 수제꼬치","0","11300","17500","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0061","img/organized_ascii/japanese/MK-20251126-0061.png","N","양념장어덮밥","0","14600","22500","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0062","img/organized_ascii/japanese/MK-20251126-0062.png","N","오뎅꼬치","0","13500","20800","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0063","img/organized_ascii/japanese/MK-20251126-0063.png","N","장어구이","0","11200","17300","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0064","img/organized_ascii/japanese/MK-20251126-0064.png","N","치킨 가라아게","0","21000","32400","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0065","img/organized_ascii/japanese/MK-20251126-0065.png","N","타코야끼","0","20700","31900","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0066","img/organized_ascii/japanese/MK-20251126-0066.png","N","타코와사비","0","18900","29200","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0067","img/organized_ascii/japanese/MK-20251126-0067.png","N","트러플 크림 뇨끼","0","19800","30500","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0068","img/organized_ascii/japanese/MK-20251126-0068.png","N","해물 오코노미야끼","0","11800","18200","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0069","img/organized_ascii/japanese/MK-20251126-0069.png","N","헤믈 나가사키 짬뽕","0","10600","16400","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0070","img/organized_ascii/japanese/MK-20251126-0070.png","N","호쿠쇼쿠 타코와사비","0","19600","30300","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0071","img/organized_ascii/japanese/MK-20251126-0071.png","N","홍게 오뎅탕","0","10700","16600","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0072","img/organized_ascii/chinese/MK-20251126-0072.png","N","고추잡채와 꽃빵","0","16000","24700","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0073","img/organized_ascii/chinese/MK-20251126-0073.png","N","교동짬뽕","0","14500","22400","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0074","img/organized_ascii/chinese/MK-20251126-0074.png","N","깐풍기","0","7700","11900","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0075","img/organized_ascii/chinese/MK-20251126-0075.png","N","꿔바로우","0","18500","28500","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0076","img/organized_ascii/chinese/MK-20251126-0076.png","N","동파육","0","10400","16100","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0077","img/organized_ascii/chinese/MK-20251126-0077.png","N","동파육만두","0","6800","10500","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0078","img/organized_ascii/chinese/MK-20251126-0078.png","N","마라 바지락 볶음","0","15900","24600","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0079","img/organized_ascii/chinese/MK-20251126-0079.png","N","마라샹궈","0","15200","23500","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0080","img/organized_ascii/chinese/MK-20251126-0080.png","N","마라탕","0","8900","13800","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0081","img/organized_ascii/chinese/MK-20251126-0081.png","N","마파두부","0","10500","16200","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0082","img/organized_ascii/chinese/MK-20251126-0082.png","N","삼겹 동파육","0","9500","14700","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0083","img/organized_ascii/chinese/MK-20251126-0083.png","N","양고기","0","16400","25300","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0084","img/organized_ascii/chinese/MK-20251126-0084.png","N","쟁반짜장","0","15400","23700","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0085","img/organized_ascii/chinese/MK-20251126-0085.png","N","중화풍 양장피","0","8500","13200","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0086","img/organized_ascii/chinese/MK-20251126-0086.png","N","중화풍 팔보채","0","17700","27300","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0087","img/organized_ascii/chinese/MK-20251126-0087.png","N","칠리새우","0","17200","26500","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0088","img/organized_ascii/chinese/MK-20251126-0088.png","N","티앤미미 동파육","0","15400","23800","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0089","img/organized_ascii/chinese/MK-20251126-0089.png","N","티앤미미 비빔새우","0","12000","18600","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0090","img/organized_ascii/chinese/MK-20251126-0090.png","N","티앤미미 새우완탕면","0","9800","15100","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0091","img/organized_ascii/chinese/MK-20251126-0091.png","N","티앤미미 우삼겹차우면","0","11800","18200","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0092","img/organized_ascii/chinese/MK-20251126-0092.png","N","향라새우","0","9500","14700","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0093","img/organized_ascii/korean/MK-20251126-0093.png","N","감자옹심이 손칼국수","0","19000","29300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0094","img/organized_ascii/korean/MK-20251126-0094.png","N","감자탕","0","24000","37000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0095","img/organized_ascii/korean/MK-20251126-0095.png","N","고추장 돼지불백","0","13700","21200","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0096","img/organized_ascii/korean/MK-20251126-0096.png","N","곱창전골","0","18700","28800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0097","img/organized_ascii/korean/MK-20251126-0097.png","N","광양식 소불고기볶음밥","0","22400","34600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0098","img/organized_ascii/korean/MK-20251126-0098.png","N","김치 콩나물 국밥","0","21700","33400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0099","img/organized_ascii/korean/MK-20251126-0099.png","N","껍데기 소금구이","0","17600","27200","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0100","img/organized_ascii/korean/MK-20251126-0100.png","N","꽃게탕 해물 된장찌개","0","13400","20700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0101","img/organized_ascii/korean/MK-20251126-0101.png","N","냉모밀소바","0","18300","28200","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0102","img/organized_ascii/korean/MK-20251126-0102.png","N","냉채족발","0","14800","22900","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0103","img/organized_ascii/korean/MK-20251126-0103.png","N","능이삼계탕","0","23400","36100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0104","img/organized_ascii/korean/MK-20251126-0104.png","N","닭내장탕","0","19300","29800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0105","img/organized_ascii/korean/MK-20251126-0105.png","N","닭발","0","17600","27200","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0106","img/organized_ascii/korean/MK-20251126-0106.png","N","닭볶음탕","0","24100","37100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0107","img/organized_ascii/korean/MK-20251126-0107.png","N","대창김치찜","0","24600","37900","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0108","img/organized_ascii/korean/MK-20251126-0108.png","N","도가니탕","0","11400","17600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0109","img/organized_ascii/korean/MK-20251126-0109.png","N","돌게&딱새우 된장찌개","0","21000","32400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0110","img/organized_ascii/korean/MK-20251126-0110.png","N","동태탕","0","12200","18800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0111","img/organized_ascii/korean/MK-20251126-0111.png","N","돼지 꼬리족발","0","19300","29800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0112","img/organized_ascii/korean/MK-20251126-0112.png","N","돼지고기 김치찜","0","24800","38300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0113","img/organized_ascii/korean/MK-20251126-0113.png","N","돼지국밥","0","15700","24300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0114","img/organized_ascii/korean/MK-20251126-0114.png","N","들기름 막국수","0","22600","34900","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0115","img/organized_ascii/korean/MK-20251126-0115.png","N","매콤 곱창볶음","0","14100","21800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0116","img/organized_ascii/korean/MK-20251126-0116.png","N","먹태구이","0","10500","16300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0117","img/organized_ascii/korean/MK-20251126-0117.png","N","멸치잔치국수","0","12600","19500","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0118","img/organized_ascii/korean/MK-20251126-0118.png","N","물냉면","0","22800","35100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0119","img/organized_ascii/korean/MK-20251126-0119.png","N","민물 장어탕","0","25000","38500","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0120","img/organized_ascii/korean/MK-20251126-0120.png","N","바지락 황태 칼국수","0","22800","35100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0121","img/organized_ascii/korean/MK-20251126-0121.png","N","바지락칼국수","0","13800","21300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0122","img/organized_ascii/korean/MK-20251126-0122.png","N","부대찌개","0","21200","32700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0123","img/organized_ascii/korean/MK-20251126-0123.png","N","부산약콩밀면","0","11000","17000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0124","img/organized_ascii/korean/MK-20251126-0124.png","N","불고기전골","0","20500","31600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0125","img/organized_ascii/korean/MK-20251126-0125.png","N","비빔 칼국수","0","12300","19000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0126","img/organized_ascii/korean/MK-20251126-0126.png","N","비빔낙지젓갈","0","16200","25000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0127","img/organized_ascii/korean/MK-20251126-0127.png","N","비빔냉면과 왕만두","0","9700","15000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0128","img/organized_ascii/korean/MK-20251126-0128.png","N","뼈해장국","0","24300","37400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0129","img/organized_ascii/korean/MK-20251126-0129.png","N","사골시래기육개장","0","16900","26100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0130","img/organized_ascii/korean/MK-20251126-0130.png","N","생아귀탕","0","19200","29600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0131","img/organized_ascii/korean/MK-20251126-0131.png","N","선지해장국","0","11100","17100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0132","img/organized_ascii/korean/MK-20251126-0132.png","N","설렁탕","0","11800","18300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0133","img/organized_ascii/korean/MK-20251126-0133.png","N","소갈비탕","0","15800","24400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0134","img/organized_ascii/korean/MK-20251126-0134.png","N","소고기 국밥","0","17900","27600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0135","img/organized_ascii/korean/MK-20251126-0135.png","N","소고기 된장전골","0","20600","31700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0136","img/organized_ascii/korean/MK-20251126-0136.png","N","소고기 샤브샤브","0","15900","24600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0137","img/organized_ascii/korean/MK-20251126-0137.png","N","소고기미역국","0","15200","23400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0138","img/organized_ascii/korean/MK-20251126-0138.png","N","소곱창","0","16600","25600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0139","img/organized_ascii/korean/MK-20251126-0139.png","N","소꼬리찜","0","24900","38400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0140","img/organized_ascii/korean/MK-20251126-0140.png","N","소불고기전골","0","18000","27700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0141","img/organized_ascii/korean/MK-20251126-0141.png","N","손말이고기","0","19600","30300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0142","img/organized_ascii/korean/MK-20251126-0142.png","N","숙성 양념 쪽갈비","0","22100","34000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0143","img/organized_ascii/korean/MK-20251126-0143.png","N","순대국","0","18000","27700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0144","img/organized_ascii/korean/MK-20251126-0144.png","N","알곤이찜","0","15800","24400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0145","img/organized_ascii/korean/MK-20251126-0145.png","N","양념 꼼장어","0","18200","28000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0146","img/organized_ascii/korean/MK-20251126-0146.png","N","양념 닭목살 볶음","0","16300","25100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0147","img/organized_ascii/korean/MK-20251126-0147.png","N","양평해장국","0","19100","29400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0148","img/organized_ascii/korean/MK-20251126-0148.png","N","언양식 소불고기","0","11500","17800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0149","img/organized_ascii/korean/MK-20251126-0149.png","N","얼큰 낙곱새","0","10000","15500","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0150","img/organized_ascii/korean/MK-20251126-0150.png","N","얼큰김치어묵우동전골","0","23400","36000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0151","img/organized_ascii/korean/MK-20251126-0151.png","N","오골계삼계탕","0","20000","30800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0152","img/organized_ascii/korean/MK-20251126-0152.png","N","오돌불갈비","0","19200","29600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0153","img/organized_ascii/korean/MK-20251126-0153.png","N","오돌뼈볶음","0","21400","33000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0154","img/organized_ascii/korean/MK-20251126-0154.png","N","오리 오고탕","0","14000","21600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0155","img/organized_ascii/korean/MK-20251126-0155.png","N","오징어볶음","0","12700","19600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0156","img/organized_ascii/korean/MK-20251126-0156.png","N","우렁강된장찌개","0","10900","16800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0157","img/organized_ascii/korean/MK-20251126-0157.png","N","우삼겹 버섯 손칼제비","0","10000","15500","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0158","img/organized_ascii/korean/MK-20251126-0158.png","N","우삼겹 청국장","0","11000","17000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0159","img/organized_ascii/korean/MK-20251126-0159.png","N","이북식 만둣국","0","14800","22800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0160","img/organized_ascii/korean/MK-20251126-0160.png","N","재첩국","0","11200","17300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0161","img/organized_ascii/korean/MK-20251126-0161.png","N","전라도 묵은지","0","16100","24800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0162","img/organized_ascii/korean/MK-20251126-0162.png","N","전라도 파김치","0","15700","24300","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0163","img/organized_ascii/korean/MK-20251126-0163.png","N","제육볶음","0","22100","34100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0164","img/organized_ascii/korean/MK-20251126-0164.png","N","즉석떡볶이","0","25200","38900","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0165","img/organized_ascii/korean/MK-20251126-0165.png","N","짱뚱어탕","0","13900","21400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0166","img/organized_ascii/korean/MK-20251126-0166.png","N","쫄면","0","23700","36600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0167","img/organized_ascii/korean/MK-20251126-0167.png","N","쫄순대볶음","0","14100","21700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0168","img/organized_ascii/korean/MK-20251126-0168.png","N","쭈꾸미 삼겹살","0","14600","22500","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0169","img/organized_ascii/korean/MK-20251126-0169.png","N","쭈꾸미볶음","0","16900","26100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0170","img/organized_ascii/korean/MK-20251126-0170.png","N","차돌떡볶이","0","24100","37200","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0171","img/organized_ascii/korean/MK-20251126-0171.png","N","채소밀키트","0","19300","29700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0172","img/organized_ascii/korean/MK-20251126-0172.png","N","총각김치","0","18500","28500","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0173","img/organized_ascii/korean/MK-20251126-0173.png","N","코다리조림","0","24500","37800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0174","img/organized_ascii/korean/MK-20251126-0174.png","N","콘치즈","0","16500","25400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0175","img/organized_ascii/korean/MK-20251126-0175.png","N","통마늘닭똥집볶음","0","13300","20600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0176","img/organized_ascii/korean/MK-20251126-0176.png","N","통영굴국","0","13300","20600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0177","img/organized_ascii/korean/MK-20251126-0177.png","N","한우 곰국","0","13700","21100","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0178","img/organized_ascii/korean/MK-20251126-0178.png","N","한우 내장전골","0","11500","17800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0179","img/organized_ascii/korean/MK-20251126-0179.png","N","한우 우족탕","0","10700","16600","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0180","img/organized_ascii/korean/MK-20251126-0180.png","N","한우우족탕","0","19300","29700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0181","img/organized_ascii/korean/MK-20251126-0181.png","N","한우육회","0","10000","15400","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0182","img/organized_ascii/korean/MK-20251126-0182.png","N","해물 부추 부침개","0","11500","17700","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0183","img/organized_ascii/korean/MK-20251126-0183.png","N","흑마늘 황칠염소탕","0","15400","23800","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0184","img/organized/한식/MK-20251126-0184.png","N","감자옹심이 손칼국수","0","23000","23000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0185","img/organized/한식/MK-20251126-0185.png","N","감자탕","10","44444","40000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0186","img/organized/한식/MK-20251126-0186.png","N","고추장 돼지불백","0","16000","16000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0187","img/organized/한식/MK-20251126-0187.png","N","곱창전골","0","30000","30000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0188","img/organized/한식/MK-20251126-0188.png","N","광양식 소불고기볶음밥","0","18000","18000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0189","img/organized/한식/MK-20251126-0189.png","N","김치 콩나물 국밥","30","25714","18000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0190","img/organized/한식/MK-20251126-0190.png","N","껍데기 소금구이","10","36667","33000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0191","img/organized/한식/MK-20251126-0191.png","N","꽃게탕 해물 된장찌개","20","25000","20000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0192","img/organized/한식/MK-20251126-0192.png","N","냉모밀소바","30","30000","21000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0193","img/organized/한식/MK-20251126-0193.png","N","냉채족발","10","25556","23000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0194","img/organized/한식/MK-20251126-0194.png","N","능이삼계탕","0","34000","34000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0195","img/organized/한식/MK-20251126-0195.png","N","닭내장탕","30","32857","23000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0196","img/organized/한식/MK-20251126-0196.png","N","닭발","30","51429","36000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0197","img/organized/한식/MK-20251126-0197.png","N","닭볶음탕","30","52857","37000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0198","img/organized/한식/MK-20251126-0198.png","N","대창김치찜","30","25714","18000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0199","img/organized/한식/MK-20251126-0199.png","N","도가니탕","30","42857","30000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0200","img/organized/한식/MK-20251126-0200.png","N","돌게&딱새우 된장찌개","10","17778","16000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0201","img/organized/한식/MK-20251126-0201.png","N","동태탕","30","52857","37000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0202","img/organized/한식/MK-20251126-0202.png","N","돼지 꼬리족발","30","54286","38000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0203","img/organized/한식/MK-20251126-0203.png","N","돼지고기 김치찜","0","26000","26000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0204","img/organized/한식/MK-20251126-0204.png","N","돼지국밥","10","31111","28000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0205","img/organized/한식/MK-20251126-0205.png","N","들기름 막국수","30","32857","23000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0206","img/organized/한식/MK-20251126-0206.png","N","매콤 곱창볶음","10","21111","19000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0207","img/organized/한식/MK-20251126-0207.png","N","먹태구이","30","47143","33000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0208","img/organized/한식/MK-20251126-0208.png","N","멸치잔치국수","10","28889","26000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0209","img/organized/한식/MK-20251126-0209.png","N","물냉면","30","34286","24000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0210","img/organized/한식/MK-20251126-0210.png","N","민물 장어탕","10","37778","34000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0211","img/organized/한식/MK-20251126-0211.png","N","바지락 황태 칼국수","20","30000","24000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0212","img/organized/한식/MK-20251126-0212.png","N","바지락칼국수","10","35556","32000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0213","img/organized/한식/MK-20251126-0213.png","N","부대찌개","10","16667","15000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0214","img/organized/한식/MK-20251126-0214.png","N","부산약콩밀면","30","45714","32000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0215","img/organized/한식/MK-20251126-0215.png","N","불고기전골","0","35000","35000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0216","img/organized/한식/MK-20251126-0216.png","N","비빔 칼국수","0","31000","31000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0217","img/organized/한식/MK-20251126-0217.png","N","비빔낙지젓갈","10","25556","23000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0218","img/organized/한식/MK-20251126-0218.png","N","비빔냉면과 왕만두","30","54286","38000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0219","img/organized/한식/MK-20251126-0219.png","N","뼈해장국","30","52857","37000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0220","img/organized/한식/MK-20251126-0220.png","N","사골시래기육개장","20","43750","35000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0221","img/organized/한식/MK-20251126-0221.png","N","생아귀탕","20","38750","31000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0222","img/organized/한식/MK-20251126-0222.png","N","선지해장국","0","25000","25000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0223","img/organized/한식/MK-20251126-0223.png","N","설렁탕","10","43333","39000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0224","img/organized/한식/MK-20251126-0224.png","N","소갈비탕","20","18750","15000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0225","img/organized/한식/MK-20251126-0225.png","N","소고기 국밥","20","27500","22000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0226","img/organized/한식/MK-20251126-0226.png","N","소고기 된장전골","0","16000","16000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0227","img/organized/한식/MK-20251126-0227.png","N","소고기 샤브샤브","0","33000","33000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0228","img/organized/한식/MK-20251126-0228.png","N","소고기미역국","0","37000","37000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0229","img/organized/한식/MK-20251126-0229.png","N","소곱창","0","29000","29000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0230","img/organized/한식/MK-20251126-0230.png","N","소꼬리찜","30","55714","39000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0231","img/organized/한식/MK-20251126-0231.png","N","소불고기전골","20","41250","33000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0232","img/organized/한식/MK-20251126-0232.png","N","손말이고기","20","37500","30000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0233","img/organized/한식/MK-20251126-0233.png","N","숙성 양념 쪽갈비","20","41250","33000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0234","img/organized/한식/MK-20251126-0234.png","N","순대국","10","17778","16000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0235","img/organized/한식/MK-20251126-0235.png","N","알곤이찜","10","41111","37000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0236","img/organized/한식/MK-20251126-0236.png","N","양념 꼼장어","0","31000","31000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0237","img/organized/한식/MK-20251126-0237.png","N","양념 닭목살 볶음","10","32222","29000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0238","img/organized/한식/MK-20251126-0238.png","N","양평해장국","10","20000","18000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0239","img/organized/한식/MK-20251126-0239.png","N","언양식 소불고기","10","27778","25000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0240","img/organized/한식/MK-20251126-0240.png","N","얼큰 낙곱새","20","50000","40000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0241","img/organized/한식/MK-20251126-0241.png","N","얼큰김치어묵우동전골","10","32222","29000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0242","img/organized/한식/MK-20251126-0242.png","N","오골계삼계탕","10","18889","17000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0243","img/organized/한식/MK-20251126-0243.png","N","오돌불갈비","30","30000","21000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0244","img/organized/한식/MK-20251126-0244.png","N","오돌뼈볶음","20","18750","15000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0245","img/organized/한식/MK-20251126-0245.png","N","오리 오고탕","20","20000","16000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0246","img/organized/한식/MK-20251126-0246.png","N","오징어볶음","0","30000","30000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0247","img/organized/한식/MK-20251126-0247.png","N","우렁강된장찌개","30","37143","26000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0248","img/organized/한식/MK-20251126-0248.png","N","우삼겹 버섯 손칼제비","0","17000","17000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0249","img/organized/한식/MK-20251126-0249.png","N","우삼겹 청국장","30","30000","21000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0250","img/organized/한식/MK-20251126-0250.png","N","이북식 만둣국","20","27500","22000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0251","img/organized/한식/MK-20251126-0251.png","N","재첩국","10","41111","37000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0252","img/organized/한식/MK-20251126-0252.png","N","전라도 묵은지","30","30000","21000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0253","img/organized/한식/MK-20251126-0253.png","N","전라도 파김치","20","37500","30000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0254","img/organized/한식/MK-20251126-0254.png","N","제육볶음","30","38571","27000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0255","img/organized/한식/MK-20251126-0255.png","N","즉석떡볶이","10","33333","30000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0256","img/organized/한식/MK-20251126-0256.png","N","짱뚱어탕","20","18750","15000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0257","img/organized/한식/MK-20251126-0257.png","N","쫄면","20","27500","22000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0258","img/organized/한식/MK-20251126-0258.png","N","쫄순대볶음","20","40000","32000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0259","img/organized/한식/MK-20251126-0259.png","N","쭈꾸미 삼겹살","10","20000","18000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0260","img/organized/한식/MK-20251126-0260.png","N","쭈꾸미볶음","20","21250","17000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0261","img/organized/한식/MK-20251126-0261.png","N","차돌떡볶이","0","36000","36000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0262","img/organized/한식/MK-20251126-0262.png","N","채소밀키트","10","34444","31000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0263","img/organized/한식/MK-20251126-0263.png","N","총각김치","20","43750","35000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0264","img/organized/한식/MK-20251126-0264.png","N","코다리조림","30","21429","15000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0265","img/organized/한식/MK-20251126-0265.png","N","콘치즈","20","50000","40000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0266","img/organized/한식/MK-20251126-0266.png","N","통마늘닭똥집볶음","30","24286","17000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0267","img/organized/한식/MK-20251126-0267.png","N","통영굴국","30","22857","16000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0268","img/organized/한식/MK-20251126-0268.png","N","한우 곰국","0","38000","38000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0269","img/organized/한식/MK-20251126-0269.png","N","한우 내장전골","10","32222","29000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0270","img/organized/한식/MK-20251126-0270.png","N","한우 우족탕","10","22222","20000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0271","img/organized/한식/MK-20251126-0271.png","N","한우우족탕","30","51429","36000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0272","img/organized/한식/MK-20251126-0272.png","N","한우육회","30","40000","28000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0273","img/organized/한식/MK-20251126-0273.png","N","해물 부추 부침개","0","35000","35000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0274","img/organized/한식/MK-20251126-0274.png","N","흑마늘 황칠염소탕","20","41250","33000","","","","","한식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0275","img/organized/중식/MK-20251126-0275.png","N","고추잡채와 꽃빵","30","18571","13000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0276","img/organized/중식/MK-20251126-0276.png","N","교동짬뽕","20","28750","23000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0277","img/organized/중식/MK-20251126-0277.png","N","깐풍기","0","13000","13000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0278","img/organized/중식/MK-20251126-0278.png","N","꿔바로우","10","12222","11000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0279","img/organized/중식/MK-20251126-0279.png","N","동파육","30","38571","27000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0280","img/organized/중식/MK-20251126-0280.png","N","동파육만두","20","33750","27000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0281","img/organized/중식/MK-20251126-0281.png","N","마라 바지락 볶음","10","12222","11000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0282","img/organized/중식/MK-20251126-0282.png","N","마라샹궈","30","42857","30000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0283","img/organized/중식/MK-20251126-0283.png","N","마라탕","20","28750","23000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0284","img/organized/중식/MK-20251126-0284.png","N","마파두부","0","18000","18000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0285","img/organized/중식/MK-20251126-0285.png","N","삼겹 동파육","30","14286","10000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0286","img/organized/중식/MK-20251126-0286.png","N","양고기","10","26667","24000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0287","img/organized/중식/MK-20251126-0287.png","N","쟁반짜장","30","30000","21000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0288","img/organized/중식/MK-20251126-0288.png","N","중화풍 양장피","10","33333","30000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0289","img/organized/중식/MK-20251126-0289.png","N","중화풍 팔보채","30","27143","19000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0290","img/organized/중식/MK-20251126-0290.png","N","칠리새우","0","28000","28000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0291","img/organized/중식/MK-20251126-0291.png","N","티앤미미 동파육","0","21000","21000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0292","img/organized/중식/MK-20251126-0292.png","N","티앤미미 비빔새우","20","30000","24000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0293","img/organized/중식/MK-20251126-0293.png","N","티앤미미 새우완탕면","0","14000","14000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0294","img/organized/중식/MK-20251126-0294.png","N","티앤미미 우삼겹차우면","30","18571","13000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0295","img/organized/중식/MK-20251126-0295.png","N","향라새우","10","21111","19000","","","","","중식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0296","img/organized/일식/MK-20251126-0296.png","N","규동 덮밥","10","18889","17000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0297","img/organized/일식/MK-20251126-0297.png","N","김재현 홈마카세 스시 밀키트","30","24286","17000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0298","img/organized/일식/MK-20251126-0298.png","N","돈코츠 라멘","10","32222","29000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0299","img/organized/일식/MK-20251126-0299.png","N","등심돈까스","20","22500","18000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0300","img/organized/일식/MK-20251126-0300.png","N","명란구이","30","52857","37000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0301","img/organized/일식/MK-20251126-0301.png","N","명란크림소바","20","48750","39000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0302","img/organized/일식/MK-20251126-0302.png","N","모츠나베","30","37143","26000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0303","img/organized/일식/MK-20251126-0303.png","N","문어가라아게","10","31111","28000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0304","img/organized/일식/MK-20251126-0304.png","N","밀푀유나베","30","57143","40000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0305","img/organized/일식/MK-20251126-0305.png","N","바질 고르곤졸라 뇨끼","30","45714","32000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0306","img/organized/일식/MK-20251126-0306.png","N","베이컨 야끼소바","30","31429","22000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0307","img/organized/일식/MK-20251126-0307.png","N","사누끼우동면","10","17778","16000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0308","img/organized/일식/MK-20251126-0308.png","N","삿포로생라멘면","30","25714","18000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0309","img/organized/일식/MK-20251126-0309.png","N","소고기 규동 밀키트","30","34286","24000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0310","img/organized/일식/MK-20251126-0310.png","N","소고기 스키야키","10","38889","35000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0311","img/organized/일식/MK-20251126-0311.png","N","소고기 야끼소바","0","25000","25000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0312","img/organized/일식/MK-20251126-0312.png","N","야끼찌꾸와","0","29000","29000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0313","img/organized/일식/MK-20251126-0313.png","N","야키토리 수제꼬치","0","23000","23000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0314","img/organized/일식/MK-20251126-0314.png","N","양념장어덮밥","10","37778","34000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0315","img/organized/일식/MK-20251126-0315.png","N","오뎅꼬치","30","31429","22000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0316","img/organized/일식/MK-20251126-0316.png","N","장어구이","10","23333","21000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0317","img/organized/일식/MK-20251126-0317.png","N","치킨 가라아게","20","42500","34000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0318","img/organized/일식/MK-20251126-0318.png","N","타코야끼","30","27143","19000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0319","img/organized/일식/MK-20251126-0319.png","N","타코와사비","10","16667","15000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0320","img/organized/일식/MK-20251126-0320.png","N","트러플 크림 뇨끼","30","30000","21000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0321","img/organized/일식/MK-20251126-0321.png","N","해물 오코노미야끼","30","21429","15000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0322","img/organized/일식/MK-20251126-0322.png","N","헤믈 나가사키 짬뽕","20","30000","24000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0323","img/organized/일식/MK-20251126-0323.png","N","호쿠쇼쿠 타코와사비","20","18750","15000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0324","img/organized/일식/MK-20251126-0324.png","N","홍게 오뎅탕","30","28571","20000","","","","","일식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0325","img/organized/양식/MK-20251126-0325.png","N","가리비 바질파스타","20","43750","35000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0326","img/organized/양식/MK-20251126-0326.png","N","갈릭비프스테이크","20","30000","24000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0327","img/organized/양식/MK-20251126-0327.png","N","감바스","20","22500","18000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0328","img/organized/양식/MK-20251126-0328.png","N","고르곤졸라피자","10","30000","27000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0329","img/organized/양식/MK-20251126-0329.png","N","매운 크림 리조또","10","28889","26000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0330","img/organized/양식/MK-20251126-0330.png","N","매운 크림 파스타","10","24444","22000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0331","img/organized/양식/MK-20251126-0331.png","N","묵은지 들기름 파스타","20","21250","17000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0332","img/organized/양식/MK-20251126-0332.png","N","베이컨토마토파스타","20","23750","19000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0333","img/organized/양식/MK-20251126-0333.png","N","봉골레 파스타","20","38750","31000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0334","img/organized/양식/MK-20251126-0334.png","N","빕스 부채살 찹스테이크","30","45714","32000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0335","img/organized/양식/MK-20251126-0335.png","N","빕스 클래식 스테이크","10","30000","27000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0336","img/organized/양식/MK-20251126-0336.png","N","새우명란오일파스타","30","24286","17000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0337","img/organized/양식/MK-20251126-0337.png","N","새우봉골레 파스타","0","14000","14000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0338","img/organized/양식/MK-20251126-0338.png","N","생크림 새우 리조또","0","29000","29000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0339","img/organized/양식/MK-20251126-0339.png","N","생크림 새우 파스타","0","29000","29000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0340","img/organized/양식/MK-20251126-0340.png","N","알렌 뼈등심스테이크&페퍼콘소스","20","33750","27000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0341","img/organized/양식/MK-20251126-0341.png","N","알렌 스피니치페스토 에그생면파스타","0","26000","26000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0342","img/organized/양식/MK-20251126-0342.png","N","알렌 아라비아따 에그생면파스타","10","18889","17000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0343","img/organized/양식/MK-20251126-0343.png","N","알리오 올리오 파스타","10","16667","15000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0344","img/organized/양식/MK-20251126-0344.png","N","차돌박이 마늘쫑 오일파스타","0","22000","22000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0345","img/organized/양식/MK-20251126-0345.png","N","찹스테이크","10","32222","29000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0346","img/organized/양식/MK-20251126-0346.png","N","트러플크림 뇨끼","30","20000","14000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0347","img/organized/양식/MK-20251126-0347.png","N","푸타네스카 파스타","10","30000","27000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0348","img/organized/양식/MK-20251126-0348.png","N","해산물 토마토 파스타","10","30000","27000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0349","img/organized/양식/MK-20251126-0349.png","N","화이트 라구 파스타","0","24000","24000","","","","","양식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0350","img/organized/건강식/MK-20251126-0350.png","N","그릴드 닭가슴살 포케","10","25556","23000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0351","img/organized/건강식/MK-20251126-0351.png","N","누룽지 삼계탕","0","14000","14000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0352","img/organized/건강식/MK-20251126-0352.png","N","능이 오리백숙","30","28571","20000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0353","img/organized/건강식/MK-20251126-0353.png","N","닭가슴살 도시락","0","19000","19000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0354","img/organized/건강식/MK-20251126-0354.png","N","닭가슴살","20","26250","21000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0355","img/organized/건강식/MK-20251126-0355.png","N","목살 스테이크 샐러드","10","8889","8000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0356","img/organized/건강식/MK-20251126-0356.png","N","보양식 해신탕","0","18000","18000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0357","img/organized/건강식/MK-20251126-0357.png","N","샐러드 마녀 비프포케 샐러드","10","12222","11000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0358","img/organized/건강식/MK-20251126-0358.png","N","세끼판다 단호박 샐러드","0","21000","21000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0359","img/organized/건강식/MK-20251126-0359.png","N","쉐이크 비프 포케 샐러드","0","19000","19000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0360","img/organized/건강식/MK-20251126-0360.png","N","쉐이크 쉬림프 포케 샐러드","30","35714","25000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0361","img/organized/건강식/MK-20251126-0361.png","N","오븐 닭가슴살","30","25714","18000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0362","img/organized/건강식/MK-20251126-0362.png","N","월남쌈","10","22222","20000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0363","img/organized/건강식/MK-20251126-0363.png","N","전복죽","10","26667","24000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0364","img/organized/건강식/MK-20251126-0364.png","N","추어탕","30","12857","9000","","","","","건강식","","","","","");
INSERT INTO contents VALUES("MK-20251126-0365","img/organized/기타/MK-20251126-0365.png","N","건어물 5종세트","30","15714","11000","","","","","기타","","","","","");
INSERT INTO contents VALUES("MK-20251126-0366","img/organized/기타/MK-20251126-0366.png","N","슈크림 붕어빵","0","26000","26000","","","","","기타","","","","","");



DROP TABLE IF EXISTS members;

CREATE TABLE `members` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `id` char(15) NOT NULL,
  `pass` varchar(450) NOT NULL,
  `name` char(10) NOT NULL,
  `phone` char(20) NOT NULL,
  `birth` char(20) DEFAULT NULL,
  `email` char(80) DEFAULT NULL,
  `refferer` char(15) DEFAULT NULL,
  `regist_day` char(20) NOT NULL,
  `level` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO members VALUES("6","song","$2y$10$WVqLToc95BzazWV/f7RLTOR4eKvCcasObOGc/FLBEpUR6tb2arKgO","송가현","01010010","","@","","2022-11-10 (08:23)","9","0");
INSERT INTO members VALUES("13","ga","$2y$10$hv5o2iKyLfDmOjHWf.kpSOj/i7LWyrz.8vHA0YX7UuUHdfRUkAHBS","ga","0104545445","","@","","2022-11-28 (05:28)","9","0");
INSERT INTO members VALUES("14","join","$2y$10$3/8Ser2GpI1xkFb.rmk07OUWbUPYscFSGLOmXbiKJJRXMr5N79SCy","join","2132","","@","","2022-12-08 (04:58)","9","0");
INSERT INTO members VALUES("15","j","$2y$10$bZKYOQgpQSkIcaSAvnmkuehOFXyCtHqaJPamtW/jvJfLuTuF4HMYq","j","1010","","@","","2022-12-08 (05:00)","9","0");
INSERT INTO members VALUES("16","dnf826","$2y$10$fb99Xs927fPIslT1cQO7ZO3fhyTNZBH/2nAtlbnW0V8PJwiTIo0xi","김우림","00000000000","030826","","","2025-11-13 (02:23)","9","0");
INSERT INTO members VALUES("17","rlatngla","$2y$10$IoncI.lrALgHCQm/9zp1..i.ITjGHLrSrOjxx7jvWnLUDRiGLGKR6","김수현","01064019455","051124","bagle97@naver.com","ghfg","2025-11-20 (05:29)","9","0");



DROP TABLE IF EXISTS pay;

CREATE TABLE `pay` (
  `order_id` varchar(50) NOT NULL,
  `orderer_name` char(10) NOT NULL,
  `orderer_email` char(80) NOT NULL,
  `orderer_phone` char(20) NOT NULL,
  `Recipient_name` char(10) NOT NULL,
  `zip_code` char(5) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `Recipient_phone` char(20) NOT NULL,
  `message` varchar(20) DEFAULT NULL,
  `member_id` char(15) NOT NULL,
  `order_contents` longtext DEFAULT NULL,
  `review` char(1) NOT NULL DEFAULT 'N',
  `status` varchar(20) DEFAULT '결제완료',
  `total_price` int(11) DEFAULT 0,
  `order_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS review;

CREATE TABLE `review` (
  `review_id` char(15) NOT NULL,
  `writer_id` char(15) NOT NULL,
  `content_code` varchar(50) NOT NULL,
  `review_contents` varchar(500) NOT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `star` int(1) DEFAULT NULL,
  `review_regdate` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO review VALUES("20221201_55a549","song","221124-010","이정도 가격에 퀄리티 짱이네요","img/reviews/8.jpeg","4","2025-11-27 09:46:57");
INSERT INTO review VALUES("20221201_9fb479","song","221124-010","상품 너무 좋네요","img/reviews/8.jpeg","5","2025-11-27 09:46:57");
INSERT INTO review VALUES("20221201_a5ddc2","song","221124-001","sdfdf","img/reviews/14.png","4","2025-11-27 09:46:57");
INSERT INTO review VALUES("20221201_eaed6f","song","221124-010","dkjkldsf","img/reviews/1.jpeg","4","2025-11-27 09:46:57");
INSERT INTO review VALUES("20221208_49a95d","song","221119-004","너무 예뻐 최고","img/reviews/2.jpeg","4","2025-11-27 09:46:57");
INSERT INTO review VALUES("20251127_c7aa6b","dnf826","MK-20251126-01","맛있어요!!!!굿굿굿","","2","2025-11-27 09:46:57");



DROP TABLE IF EXISTS wishlist;

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(64) NOT NULL,
  `content_code` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_member_code` (`member_id`,`content_code`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO wishlist VALUES("30","dnf826","MK-20251126-0366","2025-11-27 09:28:15");
INSERT INTO wishlist VALUES("32","dnf826","221124-009","2025-11-27 09:29:34");
INSERT INTO wishlist VALUES("34","dnf826","MK-20251126-0281","2025-11-27 10:13:39");
INSERT INTO wishlist VALUES("37","dnf826","MK-20251126-0273","2025-11-27 10:31:04");
INSERT INTO wishlist VALUES("38","dnf826","MK-20251126-0185","2025-11-27 10:46:30");



