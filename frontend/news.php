<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once FRONTEND_PATH . '/includes/header.php';

$article_id = $_GET['id'] ?? null;
?>

<?php if ($article_id == 4): ?>
<!-- Chi tiết bài viết Sân Cầu Lông Bảo Khang -->
<section class="page-banner">
    <div class="container">
        <h1>Tin Tức</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <a href="<?= BASE_URL ?>/news.php">Tin tức</a> / <span>Chi tiết</span>
        </nav>
    </div>
</section>

<section class="news-detail-page">
    <div class="container">
        <article class="news-article">
            <h1>Review Sân Cầu Lông Bảo Khang - Quận Tân Phú, TP.HCM</h1>
            <div class="article-meta">
                <span><i class="fas fa-calendar"></i> 01-01-2026</span>
                <span><i class="fas fa-user"></i> VNB Sports</span>
            </div>

            <div class="article-content">
                <h2>1. Giới thiệu về sân Cầu Lông Bảo Khang</h2>
                <p>Sân cầu lông Bảo Khang sở hữu <strong>4 sân thi đấu tiêu chuẩn</strong>, được đầu tư đồng bộ từ thảm sàn đến hệ thống chiếu sáng. Bề mặt sân sử dụng thảm vân đá toàn diện, mang lại độ êm tối ưu và khả năng đàn hồi cao, hỗ trợ từng bước di chuyển, giảm tải áp lực lên cổ chân và hạn chế chấn thương.</p>
                
                <p>Hệ thống đèn được bố trí khắp sân, cung cấp ánh sáng ổn định, hạn chế loá mắt và đảm bảo tầm nhìn rõ ràng trong suốt quá trình thi đấu. Tông sơn xanh bao quanh tạo cảm giác dễ chịu cho mắt, đồng thời giúp người chơi quan sát đường cầu rõ ràng hơn.</p>
                
                <p>Không gian sân cầu lông Bảo Khang cũng được chú trọng về thiết kế, với trần cao khoảng <strong>8 mét</strong> cho phép thực hiện các cú phông cao một cách thoải mái mà không gặp trở ngại. Đặc biệt, kiến trúc mở giúp âm vang tiếng cầu nổ rõ rệt, mang lại cảm giác hưng phấn và thúc đẩy tinh thần thi đấu.</p>

                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Thông tin sân Cầu Lông Bảo Khang:</h4>
                    <ul>
                        <li><strong>Địa chỉ:</strong> 71 Đ. Quách Đình Bảo, Phú Thạnh, Tân Phú, Thành phố Hồ Chí Minh</li>
                        <li><strong>Link map:</strong> <a href="https://maps.app.goo.gl/31H13YW8BxWW14iu5" target="_blank">Xem bản đồ</a></li>
                        <li><strong>Quy mô:</strong> 4 sân cầu lông</li>
                        <li><strong>Thời gian hoạt động:</strong> 5h30 - 22h</li>
                    </ul>
                </div>

                <h2>2. Giá thuê sân Cầu Lông Bảo Khang</h2>
                <p>Giá thuê sân cầu lông Bảo Khang dao động từ <strong>60.000 - 110.000đ/giờ</strong> tuỳ vào hình thức đặt sân (cố định hay vãng lai) và khung giờ chơi. Thông thường, khung giờ vào cuối tuần và buổi tối sẽ có mức giá cao hơn so với ban ngày.</p>

                <h2>3. Các dịch vụ tiện ích tại sân Cầu Lông Bảo Khang</h2>
                
                <div class="service-item">
                    <h4><i class="fas fa-parking"></i> Khu gửi xe rộng và sạch sẽ</h4>
                    <p>Khu gửi xe rộng và sạch sẽ, có mái che, đảm bảo thuận tiện và an toàn cho người chơi.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-restroom"></i> Nhà vệ sinh và phòng tắm</h4>
                    <p>Nhà vệ sinh riêng cho nam nữ, khu thay đồ và phòng tắm được trang bị bình nước nóng, phục vụ nhu cầu vệ sinh cá nhân sau khi tập luyện.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-couch"></i> Hàng ghế chờ thoáng rộng</h4>
                    <p>Hàng ghế chờ thoáng rộng, bố trí hợp lý giúp vận động viên nghỉ giữa hiệp thoải mái.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-coffee"></i> Quầy đồ uống và khu ngồi nghỉ</h4>
                    <p>Quầy đồ uống và khu ngồi nghỉ tiện lợi, phục vụ nước giải khát và chỗ ngồi thư giãn sau trận đấu.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-lock"></i> Hệ thống tủ gửi đồ khóa số</h4>
                    <p>Hệ thống tủ gửi đồ khóa số hiện đại, bảo đảm sự riêng tư và an tâm cho người sử dụng.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-wifi"></i> Wifi tốc độ cao miễn phí</h4>
                    <p>Wifi tốc độ cao miễn phí, cùng ổ cắm điện hỗ trợ sạc thiết bị cá nhân khi cần.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-trophy"></i> Tổ chức giải phong trào</h4>
                    <p>Nhận tổ chức giải phong trào quy mô vừa và nhỏ, hỗ trợ cộng đồng cầu lông giao lưu.</p>
                </div>

                <div class="article-footer">
                    <p>Bài viết trên là phần đánh giá chi tiết của VNB Sports về sân cầu lông Bảo Khang. Nếu bạn đang tìm kiếm thêm các sân chơi cầu lông ở khu vực quận Tân Phú, hãy liên hệ với chúng tôi để được tư vấn thêm.</p>
                </div>
            </div>
        </article>
    </div>
</section>

<?php elseif ($article_id == 3): ?>
<!-- Chi tiết bài viết Sân Cầu Lông Văn Hiền -->
<section class="page-banner">
    <div class="container">
        <h1>Tin Tức</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <a href="<?= BASE_URL ?>/news.php">Tin tức</a> / <span>Chi tiết</span>
        </nav>
    </div>
</section>

<section class="news-detail-page">
    <div class="container">
        <article class="news-article">
            <h1>Review Sân Cầu Lông Văn Hiền - Thủ Dầu Một, Bình Dương</h1>
            <div class="article-meta">
                <span><i class="fas fa-calendar"></i> 01-01-2026</span>
                <span><i class="fas fa-user"></i> VNB Sports</span>
            </div>

            <div class="article-content">
                <h2>1. Giới thiệu về sân Cầu Lông Văn Hiền</h2>
                <p>Sân cầu lông Văn Hiền được hiển thị trên Google Maps là "Sân cầu lông Ô tô Văn Hiền", đây là địa điểm lý tưởng dành cho những người đam mê cầu lông mong muốn trải nghiệm môi trường tập luyện chuyên nghiệp. Khai trương vào năm 2022, sân hướng đến tiêu chuẩn hiện đại với không gian rộng rãi, thiết kế chỉn chu và cảm giác thi đấu tương tự các giải đấu quốc tế.</p>
                
                <p>Khu tổ hợp gồm <strong>9 sân</strong>, chia thành hai khu A và B: trong đó 6 sân đạt chuẩn thi đấu và 3 sân phục vụ mục đích tập luyện. Cơ sở này còn tích hợp phòng thay đồ, khu tắm tiện nghi, khu vực nghỉ ngơi, máy bán nước tự động cùng dịch vụ cho thuê trang phục và dụng cụ cầu lông.</p>
                
                <p>Toàn bộ mặt sân cầu lông Văn Hiền được trang bị thảm vân đá đạt chuẩn, hệ thống đèn chiếu sáng chất lượng cao và lưới căng đúng quy cách, đáp ứng yêu cầu tập luyện và thi đấu nghiêm túc. Không gian sân được thiết kế thông thoáng với trần cao và kết cấu kiên cố, kết hợp hệ thống thông gió và chống nóng hiệu quả, giúp duy trì bầu không khí dễ chịu trong suốt quá trình vận động.</p>
                
                <p>Diện tích các khu sân vừa phải nhưng được phân bổ khoa học, đảm bảo lối di chuyển thuận tiện giữa các sân, tạo điều kiện tối ưu cho người chơi trong giờ luyện tập và thi đấu.</p>

                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Thông tin sân Cầu Lông Văn Hiền:</h4>
                    <ul>
                        <li><strong>Địa chỉ:</strong> 264/87 Nguyễn Thị Minh Khai, Tổ 1, Khu 3, Thủ Dầu Một, Bình Dương</li>
                        <li><strong>Link map:</strong> <a href="https://maps.app.goo.gl/Fe25NTjtJfARYzaM9" target="_blank">Xem bản đồ</a></li>
                        <li><strong>Quy mô:</strong> 9 sân cầu lông</li>
                        <li><strong>Thời gian hoạt động:</strong> 5h - 22h</li>
                    </ul>
                </div>

                <h2>2. Giá thuê sân Cầu Lông Văn Hiền</h2>
                <p>Bảng giá thuê sân cầu lông Văn Hiền dao động từ <strong>50.000 - 85.000đ/giờ</strong> tuỳ thuộc vào khung giờ chơi trong ngày. Đây là mức giá hợp lý và phải chăng tại khu vực Bình Dương, phù hợp với cả học sinh sinh viên.</p>

                <h2>3. Các dịch vụ tiện ích tại sân Cầu Lông Văn Hiền</h2>
                
                <div class="service-item">
                    <h4><i class="fas fa-wifi"></i> Wifi tốc độ cao miễn phí</h4>
                    <p>Wifi tốc độ cao miễn phí cho toàn bộ khu vực sân cầu lông Văn Hiền, giúp người chơi dễ dàng tra cứu thông tin, giải trí hoặc làm việc trong lúc chờ.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-parking"></i> Bãi đỗ xe rộng rãi</h4>
                    <p>Bãi đỗ xe rộng rãi dành cho cả ô tô và xe máy, có mái che bảo vệ phương tiện, kèm đội ngũ bảo vệ túc trực.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-tshirt"></i> Phòng thay đồ sạch sẽ</h4>
                    <p>Phòng thay đồ sạch sẽ, khang trang, trang bị tủ đựng đồ cá nhân, tạo sự riêng tư và tiện lợi cho người chơi trước và sau khi tập luyện.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-shower"></i> Khu vực tắm hiện đại</h4>
                    <p>Khu vực tắm hiện đại, đầy đủ vòi sen, nước nóng lạnh hỗ trợ phục hồi thể lực thoải mái sau buổi chơi.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-couch"></i> Không gian nghỉ ngơi thoáng mát</h4>
                    <p>Không gian nghỉ ngơi thoáng mát, bố trí ghế ngồi và bàn chờ rộng rãi, phù hợp thư giãn giữa các set đấu.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-bottle-water"></i> Máy bán nước tự động</h4>
                    <p>Máy bán nước tự động với nhiều lựa chọn như nước khoáng, nước ion, nước tăng lực… phục vụ nhanh chóng khi cần bổ sung năng lượng.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-table-tennis"></i> Dịch vụ cho thuê dụng cụ</h4>
                    <p>Dịch vụ cho thuê dụng cụ cầu lông, bao gồm vợt, cầu, băng quấn tay và cả đồng phục thể thao.</p>
                </div>

                <div class="article-footer">
                    <p>Bài viết trên là phần đánh giá chi tiết của VNB Sports về sân cầu lông Văn Hiền. Nếu bạn đang tìm kiếm thêm các sân chơi cầu lông ở khu vực Bình Dương, hãy liên hệ với chúng tôi để được tư vấn thêm.</p>
                </div>
            </div>
        </article>
    </div>
</section>

<?php elseif ($article_id == 2): ?>
<!-- Chi tiết bài viết Ecosport Gò Dầu -->
<section class="page-banner">
    <div class="container">
        <h1>Tin Tức</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <a href="<?= BASE_URL ?>/news.php">Tin tức</a> / <span>Chi tiết</span>
        </nav>
    </div>
</section>

<section class="news-detail-page">
    <div class="container">
        <article class="news-article">
            <h1>Review Sân Cầu Lông Ecosport Gò Dầu - Quận Tân Phú, TP.HCM</h1>
            <div class="article-meta">
                <span><i class="fas fa-calendar"></i> 01-01-2026</span>
                <span><i class="fas fa-user"></i> VNB Sports</span>
            </div>

            <div class="article-content">
                <h2>1. Giới thiệu về sân cầu lông Ecosport Gò Dầu</h2>
                <p>Sân cầu lông Ecosport Gò Dầu sở hữu tổng cộng <strong>8 sân chơi tiêu chuẩn</strong>, bao gồm một sân private dành cho những ai muốn trải nghiệm không gian riêng tư, yên tĩnh. Không gian sân rộng rãi, thoáng đãng, mặt sân được lát chuẩn quốc tế với màu xanh lá tươi sáng, tạo cảm giác hài hòa và dễ chịu khi thi đấu.</p>
                
                <p>Hệ thống đèn LED Panel cao cấp được lắp đặt đầy đủ khắp sân, đảm bảo ánh sáng đồng đều và rõ ràng, giúp người chơi có thể luyện tập và thi đấu vào bất kỳ thời điểm nào trong ngày, kể cả buổi tối. Trần sân cao khoảng <strong>15m</strong> kết hợp với lớp cách nhiệt, giúp không gian luôn thoáng mát và hạn chế va chạm. Khoảng cách giữa các sân rộng rãi, mang lại trải nghiệm chơi thoải mái, an toàn và thuận tiện cho mọi đối tượng người chơi.</p>
                
                <p>Ngoài ra, khu vực nghỉ ngơi thoáng mát được bố trí ngay cạnh sân, giúp người chơi có thể nghỉ ngơi và quan sát trận đấu. Sân cầu lông Ecosport Gò Dầu còn có căn tin cung cấp các loại nước uống giải khát cho các lông thủ.</p>

                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Thông tin sân cầu lông Ecosport Gò Dầu:</h4>
                    <ul>
                        <li><strong>Địa chỉ:</strong> 185/22 Đ. Gò Dầu, Tân Quý, Tân Phú, Thành phố Hồ Chí Minh</li>
                        <li><strong>Link map:</strong> <a href="https://maps.app.goo.gl/a4smcaZCKZE2zMcc9" target="_blank">Xem bản đồ</a></li>
                        <li><strong>Quy mô:</strong> 8 sân</li>
                        <li><strong>Thời gian hoạt động:</strong> 5h - 23h</li>
                    </ul>
                </div>

                <h2>2. Giá thuê sân cầu lông Ecosport Gò Dầu</h2>
                <p>Giá thuê sân cầu lông Ecosport Gò Dầu linh hoạt, dao động từ <strong>60.000 – 120.000đ/giờ</strong>, tùy theo khung giờ. Thường các buổi tối sẽ có mức giá cao hơn so với ban ngày. Đặc biệt, khi thuê sân chơi 2 giờ, bạn sẽ được tặng kèm 1 bình trà đá miễn phí, vừa giải khát vừa tăng thêm hứng thú luyện tập.</p>

                <h2>3. Các dịch vụ tiện ích tại sân cầu lông Ecosport Gò Dầu</h2>
                
                <div class="service-item">
                    <h4><i class="fas fa-parking"></i> Bãi giữ xe rộng rãi</h4>
                    <p>Sân được trang bị bãi đỗ xe thoáng đãng, tiện lợi cho cả ô tô và xe máy, giúp người chơi yên tâm khi đến tham gia tập luyện hay thi đấu.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-coffee"></i> Quầy căn tin</h4>
                    <p>Nơi đây cung cấp đa dạng các loại nước uống giải khát, giúp bạn bổ sung năng lượng ngay khi cần, đồng thời tạo không gian thư giãn nhẹ nhàng sau mỗi trận đấu.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-restroom"></i> Nhà vệ sinh sạch sẽ</h4>
                    <p>Khu vực nhà vệ sinh được vệ sinh thường xuyên, phân chia rõ ràng cho nam và nữ, đảm bảo tiện nghi và vệ sinh cho người chơi.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-lightbulb"></i> Hệ thống đèn LED Panel</h4>
                    <p>Ánh sáng được bố trí đồng đều trên toàn sân, đảm bảo người chơi có thể luyện tập và thi đấu vào bất kỳ thời điểm nào trong ngày, kể cả buổi tối.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-couch"></i> Khu vực nghỉ ngơi thoáng mát</h4>
                    <p>Nằm ngay cạnh sân, khu vực này được bố trí ghế ngồi thoải mái, tạo không gian thư giãn, theo dõi trận đấu hoặc chờ đến lượt chơi.</p>
                </div>

                <p><strong>Đọc thêm:</strong> <a href="#">Sân Ecosport chi nhánh Gò Vấp</a></p>

                <h2>4. Đánh giá của người chơi tại sân cầu lông Ecosport Gò Dầu</h2>

                <div class="article-footer">
                    <p>Bài viết vừa rồi đã mang đến cho bạn những thông tin chi tiết về sân là review của VNB Sports về sân cầu lông Ecosport Gò Dầu. Nếu bạn muốn tìm kiếm những sân chơi cầu lông khác trong khu vực lân cận quận Tân Phú, đừng bỏ qua bài viết: <a href="#">Danh sách các sân cầu lông Tân Phú - Cập nhật mới nhất 2025</a></p>
                </div>
            </div>
        </article>
    </div>
</section>

<?php elseif ($article_id == 1): ?>
<!-- Chi tiết bài viết -->
<section class="page-banner">
    <div class="container">
        <h1>Tin Tức</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <a href="<?= BASE_URL ?>/news.php">Tin tức</a> / <span>Chi tiết</span>
        </nav>
    </div>
</section>

<section class="news-detail-page">
    <div class="container">
        <article class="news-article">
            <h1>Review Sân Cầu Lông City Sports - Quận 12, TP.HCM</h1>
            <div class="article-meta">
                <span><i class="fas fa-calendar"></i> 01-01-2026</span>
                <span><i class="fas fa-user"></i> VNB Sports</span>
            </div>

            <div class="article-content">
                <h2>1. Giới thiệu về sân cầu lông City Sports</h2>
                <p>Sân cầu lông City Sports được đầu tư với hệ thống <strong>4 sân đạt chuẩn</strong>, đáp ứng tốt nhu cầu luyện tập lẫn thi đấu phong trào. Không gian bên trong rộng rãi và thông thoáng, mặt sân phủ lớp thảm chuẩn quốc tế với tông màu xám hiện đại.</p>
                
                <p>Hệ thống đèn LED Panel bản lớn được bố trí đều trên trần, cung cấp ánh sáng ổn định và không gây lóa, đảm bảo người chơi có thể yên tâm tập luyện từ sáng đến tối. Trần sân được thiết kế cao tạo độ thoáng, hạn chế tối đa các tình huống cầu chạm trần, một điểm cộng lớn cho những buổi đánh cầu có tốc độ cao.</p>
                
                <p>Khoảng cách giữa các sân cầu lông City Sports được tính toán hợp lý, không gây cảm giác chật chội hay ảnh hưởng lẫn nhau giữa các trận đấu. Quạt mát được lắp đặt rải rác giúp không khí luôn dễ chịu, ngay cả khi sân đông người.</p>
                
                <p>Ngay bên cạnh khu vực thi đấu là khu nghỉ ngơi thoáng mát, nơi người chơi có thể ngồi quan sát trận đấu hoặc hồi sức giữa các hiệp. Bên trong sân còn có căn tin nhỏ, phục vụ nước uống và các sản phẩm giải khát, rất tiện lợi cho các lông thủ trong những buổi tập kéo dài.</p>
                
                <p>Tuy vậy, một điểm trừ nhỏ là tường sân sơn màu vàng nhẹ khá sáng, đôi lúc có thể gây chói mắt khi theo dõi những pha cầu tốc độ cao. Dù vậy, tổng thể trải nghiệm tại City Sports vẫn được đánh giá tích cực.</p>
                
                <p>Không chỉ có cầu lông, City Sports còn sở hữu <strong>6 sân Pickleball</strong> riêng biệt, mang đến lựa chọn phong phú cho những ai muốn thử sức với bộ môn đang ngày càng phổ biến này.</p>

                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Thông tin sân cầu lông City Sports:</h4>
                    <ul>
                        <li><strong>Địa chỉ:</strong> 1966/5D QL1A, Tân Thới Hiệp, Quận 12, Thành phố Hồ Chí Minh</li>
                        <li><strong>Link map:</strong> <a href="https://maps.app.goo.gl/V5hTjtkfktyXymXk9" target="_blank">Xem bản đồ</a></li>
                        <li><strong>Quy mô:</strong> 4 sân cầu lông</li>
                        <li><strong>Thời gian hoạt động:</strong> 6h - 22h</li>
                    </ul>
                </div>

                <h2>2. Giá thuê sân cầu lông City Sports</h2>
                <p>Vì vừa đưa vào hoạt động khu vực cầu lông, sân cầu lông City Sports hiện áp dụng mức giá ưu đãi đặc biệt, chỉ từ <strong>35.000 – 45.000đ/giờ</strong> tuỳ theo từng khung giờ. Đây được xem là mức giá khá cạnh tranh so với mặt bằng chung, phù hợp cho người chơi muốn trải nghiệm sân mới với chi phí hợp lý.</p>

                <h2>3. Các dịch vụ tiện ích tại sân cầu lông City Sports</h2>
                
                <div class="service-item">
                    <h4><i class="fas fa-parking"></i> Bãi đỗ xe rộng rãi</h4>
                    <p>City Sports sở hữu khu vực gửi xe thoáng đãng, hỗ trợ cả ô tô lẫn xe máy. Người chơi có thể yên tâm di chuyển và tập trung vào buổi tập hoặc trận đấu mà không lo vấn đề chỗ để xe.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-coffee"></i> Quầy căn tin tiện lợi</h4>
                    <p>Khu căn tin phục vụ đa dạng nước uống giải khát, giúp bổ sung năng lượng kịp thời sau mỗi hiệp đấu. Đây cũng là không gian thư giãn nhẹ nhàng cho các nhóm bạn hoặc vận động viên chờ lượt.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-restroom"></i> Nhà vệ sinh sạch sẽ</h4>
                    <p>Hệ thống nhà vệ sinh được vệ sinh thường xuyên, phân khu rõ ràng cho nam và nữ. Không gian gọn gàng, sạch sẽ tạo cảm giác thoải mái và đảm bảo vệ sinh cho người sử dụng.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-lightbulb"></i> Hệ thống đèn LED Panel đồng đều</h4>
                    <p>Toàn bộ sân cầu lông City Sports được trang bị đèn LED bản lớn, cho ánh sáng rõ ràng, không chói mắt. Nhờ đó, người chơi có thể thi đấu ở bất kỳ thời điểm nào, kể cả vào buổi tối mà vẫn đảm bảo chất lượng quan sát.</p>
                </div>

                <div class="service-item">
                    <h4><i class="fas fa-couch"></i> Khu vực nghỉ ngơi thoáng mát</h4>
                    <p>Nằm tách biệt vừa đủ với khu vực thi đấu, khu nghỉ ngơi được bố trí ghế ngồi thoải mái, thuận tiện cho việc hồi sức, trò chuyện hoặc quan sát các trận đấu diễn ra trên sân.</p>
                </div>

                <div class="article-footer">
                    <p>Bài viết trên là phần đánh giá chi tiết của VNB Sports về sân cầu lông City Sports. Nếu bạn đang tìm kiếm thêm các sân chơi cầu lông ở khu vực lân cận quận 12, đừng bỏ qua bài viết: <a href="#">Danh sách các sân cầu lông quận 12 - Cập nhật mới nhất 2025</a></p>
                </div>
            </div>
        </article>
    </div>
</section>

<?php else: ?>
<!-- Danh sách tin tức -->
<section class="page-banner">
    <div class="container">
        <h1>Tin Tức</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <span>Tin tức</span>
        </nav>
    </div>
</section>

<section class="news-page">
    <div class="container">
        <div class="news-grid-large">
            <article class="news-card-large">
                <a href="<?= BASE_URL ?>/news.php?id=1" class="news-image">
                    <img src="/shopcaulong/images/anh1.jpg" alt="Sân cầu lông City Sports">
                </a>
                <div class="news-content">
                    <span class="news-date"><i class="fas fa-calendar"></i> 01-01-2026</span>
                    <h3><a href="<?= BASE_URL ?>/news.php?id=1">Review Sân Cầu Lông City Sports - Quận 12, TP.HCM</a></h3>
                    <p>Sân cầu lông City Sports được đầu tư với hệ thống 4 sân đạt chuẩn, đáp ứng tốt nhu cầu luyện tập lẫn thi đấu phong trào...</p>
                    <a href="<?= BASE_URL ?>/news.php?id=1" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>

            <article class="news-card-large">
                <a href="<?= BASE_URL ?>/news.php?id=2" class="news-image">
                    <img src="/shopcaulong/images/anh3.jpg" alt="Sân cầu lông Ecosport Gò Dầu">
                </a>
                <div class="news-content">
                    <span class="news-date"><i class="fas fa-calendar"></i> 01-01-2026</span>
                    <h3><a href="<?= BASE_URL ?>/news.php?id=2">Review Sân Cầu Lông Ecosport Gò Dầu - Quận Tân Phú, TP.HCM</a></h3>
                    <p>Sân cầu lông Ecosport Gò Dầu sở hữu tổng cộng 8 sân chơi tiêu chuẩn, bao gồm một sân private dành cho những ai muốn trải nghiệm không gian riêng tư...</p>
                    <a href="<?= BASE_URL ?>/news.php?id=2" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>

            <article class="news-card-large">
                <a href="<?= BASE_URL ?>/news.php?id=3" class="news-image">
                    <img src="/shopcaulong/images/anh2.jpg" alt="Sân cầu lông Văn Hiền">
                </a>
                <div class="news-content">
                    <span class="news-date"><i class="fas fa-calendar"></i> 01-01-2026</span>
                    <h3><a href="<?= BASE_URL ?>/news.php?id=3">Review Sân Cầu Lông Văn Hiền - Thủ Dầu Một, Bình Dương</a></h3>
                    <p>Sân cầu lông Văn Hiền là địa điểm lý tưởng dành cho những người đam mê cầu lông mong muốn trải nghiệm môi trường tập luyện chuyên nghiệp với 9 sân tiêu chuẩn...</p>
                    <a href="<?= BASE_URL ?>/news.php?id=3" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
        <article class="news-card-large">
                <a href="<?= BASE_URL ?>/news.php?id=4" class="news-image">
                    <img src="/shopcaulong/images/anh4.jpg" alt="Sân cầu lông Bảo Khang">
                </a>
                <div class="news-content">
                    <span class="news-date"><i class="fas fa-calendar"></i> 01-01-2026</span>
                    <h3><a href="<?= BASE_URL ?>/news.php?id=4">Review Sân Cầu Lông Bảo Khang - Quận Tân Phú, TP.HCM</a></h3>
                    <p>Sân cầu lông Bảo Khang sở hữu 4 sân thi đấu tiêu chuẩn, được đầu tư đồng bộ từ thảm sàn đến hệ thống chiếu sáng...</p>
                    <a href="<?= BASE_URL ?>/news.php?id=4" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
