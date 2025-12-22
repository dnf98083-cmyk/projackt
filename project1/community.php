<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/session.php';

$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE === '') $BASE = '/';

// 탭 필터 (기본값: 전체)
$tab = $_GET['tab'] ?? 'all';

// 정렬 옵션
$sort = $_GET['sort'] ?? 'latest';

// 검색어
$search_query = $_GET['q'] ?? '';

// 페이지네이션
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// 리뷰 데이터 가져오기
$where_clause = "1=1";
$params = [];

// 검색어 필터
if (!empty($search_query)) {
    $where_clause .= " AND c.content_name LIKE ?";
    $params[] = "%" . $search_query . "%";
}

if ($tab === 'photo') {
    $where_clause .= " AND r.photo IS NOT NULL AND r.photo != ''";
} elseif ($tab === 'high') {
    $where_clause .= " AND r.star >= 4";
} elseif ($tab === 'low') {
    $where_clause .= " AND r.star <= 2";
}

// 정렬
$order_by = "r.review_regdate DESC";
if ($sort === 'star_high') {
    $order_by = "r.star DESC, r.review_regdate DESC";
} elseif ($sort === 'star_low') {
    $order_by = "r.star ASC, r.review_regdate DESC";
} elseif ($sort === 'comments') {
    $order_by = "comment_count DESC, r.review_regdate DESC";
} elseif ($sort === 'views') {
    $order_by = "r.views DESC, r.review_regdate DESC";
}

// 전체 개수 조회
$count_query = "
    SELECT COUNT(*) as total 
    FROM review r 
    LEFT JOIN contents c ON r.content_code = c.content_code 
    WHERE $where_clause
";
$count_result = db_select($count_query, $params);
$total_count = $count_result[0]['total'] ?? 0;
$total_pages = ceil($total_count / $limit);

// 리뷰 목록 조회
$query = "
    SELECT 
        r.*,
        c.content_name,
        c.content_img,
        m.name as writer_name,
        (SELECT COUNT(*) FROM review_comments rc WHERE rc.review_id = r.review_id) as comment_count
    FROM review r
    LEFT JOIN contents c ON r.content_code = c.content_code
    LEFT JOIN members m ON r.writer_id = m.id
    WHERE $where_clause
    ORDER BY $order_by
    LIMIT $limit OFFSET $offset
";
$reviews = db_select($query, $params);
if (!is_array($reviews)) $reviews = [];

// 캐시 버스팅
function bust($relPath) {
    $abs = __DIR__ . '/' . ltrim($relPath, '/');
    $ver = is_file($abs) ? filemtime($abs) : time();
    return $relPath . '?v=' . $ver;
}

// 별점 HTML 생성
function generate_stars($rating) {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fas fa-star filled"></i>';
        } else {
            $html .= '<i class="far fa-star"></i>';
        }
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>커뮤니티 - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= bust('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <style>
        .community-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .community-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .community-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .community-header p {
            color: #666;
            font-size: 16px;
        }
        
        /* 탭 메뉴 */
        .community-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 0;
        }
        
        .community-tabs a {
            padding: 12px 24px;
            text-decoration: none;
            color: #666;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .community-tabs a:hover {
            color: var(--mk-red);
        }
        
        .community-tabs a.active {
            color: var(--mk-red);
            border-bottom-color: var(--mk-red);
            font-weight: 700;
        }
        
        /* 필터 바 */
        .community-filter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        .filter-left {
            font-size: 14px;
            color: #666;
        }
        
        .filter-left strong {
            color: var(--mk-red);
            font-weight: 700;
        }
        
        .filter-right select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        /* 리뷰 그리드 */
        .review-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .review-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .review-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .review-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f5f5f5;
            position: relative;
        }
        
        .review-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .review-image .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #ccc;
            font-size: 48px;
        }
        
        .review-content {
            padding: 16px;
        }
        
        .review-product {
            font-size: 12px;
            color: #999;
            margin-bottom: 6px;
        }
        
        .review-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .review-rating .stars {
            color: #ffc107;
            font-size: 14px;
        }
        
        .review-rating .stars i {
            margin-right: 2px;
        }
        
        .review-rating .date {
            font-size: 12px;
            color: #999;
            margin-left: auto;
        }
        
        .review-text {
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .review-author {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }
        
        /* 빈 상태 */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        .empty-state p {
            font-size: 16px;
        }
        
        /* 페이지네이션 */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 40px;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: all 0.2s;
        }
        
        .pagination a:hover {
            background: var(--mk-red);
            color: white;
            border-color: var(--mk-red);
        }
        
        .pagination .current {
            background: var(--mk-red);
            color: white;
            border-color: var(--mk-red);
            font-weight: 700;
        }
        
        .stars .filled {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/inc/header.php'; ?>
    
    <main class="main_wrapper">
        <div class="community-wrapper">
            <div class="community-header">
                <h1>커뮤니티</h1>
                <p>고객님들의 생생한 상품 후기를 확인해보세요</p>
            </div>
            
            <!-- 탭 메뉴 -->
            <div class="community-tabs">
                <a href="?tab=all<?= $sort !== 'latest' ? '&sort='.$sort : '' ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>" class="<?= $tab === 'all' ? 'active' : '' ?>">
                    전체 후기
                </a>
                <a href="?tab=photo<?= $sort !== 'latest' ? '&sort='.$sort : '' ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>" class="<?= $tab === 'photo' ? 'active' : '' ?>">
                    포토 후기
                </a>
                <a href="?tab=high<?= $sort !== 'latest' ? '&sort='.$sort : '' ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>" class="<?= $tab === 'high' ? 'active' : '' ?>">
                    베스트 후기
                </a>
                <a href="?tab=low<?= $sort !== 'latest' ? '&sort='.$sort : '' ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>" class="<?= $tab === 'low' ? 'active' : '' ?>">
                    개선 후기
                </a>
            </div>
            
            <!-- 검색창 -->
            <div class="community-search" style="margin-bottom: 20px; text-align: right;">
                <form action="" method="GET" style="display: inline-block;">
                    <input type="hidden" name="tab" value="<?= $tab ?>">
                    <input type="hidden" name="sort" value="<?= $sort ?>">
                    <div style="position: relative; display: inline-block;">
                        <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="상품명 검색" style="padding: 8px 35px 8px 15px; width: 250px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 14px; transition: border-color 0.2s;">
                        <button type="submit" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer; color: #666;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <style>
                    .community-search input:focus { border-color: var(--mk-red); }
                    .community-search button:hover { color: var(--mk-red); }
                </style>
            </div>
            
            <!-- 필터 바 -->
            <div class="community-filter">
                <div class="filter-left">
                    <?php if(!empty($search_query)): ?>
                        '<strong><?= htmlspecialchars($search_query) ?></strong>' 검색 결과 
                    <?php endif; ?>
                    총 <strong><?= number_format($total_count) ?></strong>개의 후기
                </div>
                <div class="filter-right">
                    <select onchange="location.href='?tab=<?= $tab ?>&q=<?= urlencode($search_query) ?>&sort=' + this.value + '&page=1'">
                        <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>최신순</option>
                        <option value="star_high" <?= $sort === 'star_high' ? 'selected' : '' ?>>별점 높은순</option>
                        <option value="star_low" <?= $sort === 'star_low' ? 'selected' : '' ?>>별점 낮은순</option>
                        <option value="comments" <?= $sort === 'comments' ? 'selected' : '' ?>>댓글순</option>
                        <option value="views" <?= $sort === 'views' ? 'selected' : '' ?>>조회순</option>
                    </select>
                </div>
            </div>
            
            <!-- 리뷰 그리드 -->
            <?php if (empty($reviews)): ?>
                <div class="empty-state">
                    <i class="far fa-comments"></i>
                    <p>아직 작성된 후기가 없습니다.</p>
                </div>
            <?php else: ?>
                <div class="review-grid">
                    <?php foreach ($reviews as $review): 
                        $current_user_id = $_SESSION['member_id'] ?? '';
                        $is_admin = is_manager();
                        $is_writer = ($current_user_id === $review['writer_id']);

                        $review_data = [
                            'id' => $review['review_id'],
                            'writer' => mask_id($review['writer_id']) . ' (' . mask_name($review['writer_name'] ?? '') . ')',
                            'content' => nl2br(htmlspecialchars($review['review_contents'])),
                            'date' => date('Y.m.d', strtotime($review['review_regdate'] ?? 'now')),
                            'star' => $review['star'],
                            'product_name' => $review['content_name'],
                            'product_img' => $review['content_img'],
                            'photo' => $review['photo'],
                            'can_delete' => ($is_admin || $is_writer)
                        ];
                        $json_data = htmlspecialchars(json_encode($review_data), ENT_QUOTES, 'UTF-8');
                    ?>
                        <div class="review-card" onclick="openReviewModal(this)" data-review='<?= $json_data ?>'>
                            <div class="review-image">
                                <?php if (!empty($review['photo'])): ?>
                                    <img src="<?= htmlspecialchars($review['photo']) ?>" alt="리뷰 이미지">
                                <?php elseif (!empty($review['content_img'])): ?>
                                    <img src="<?= htmlspecialchars($review['content_img']) ?>" alt="상품 이미지">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="far fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="review-content">
                                <div class="review-product">
                                    <?= htmlspecialchars($review['content_name'] ?? '상품명 없음') ?>
                                </div>
                                <div class="review-rating">
                                    <div class="stars">
                                        <?= generate_stars($review['star']) ?>
                                    </div>
                                    <div class="date">
                                        <?= date('Y.m.d', strtotime($review['review_regdate'] ?? 'now')) ?>
                                    </div>
                                </div>
                                <div class="review-text">
                                    <?= nl2br(htmlspecialchars($review['review_contents'])) ?>
                                </div>
                                <div class="review-footer" style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; font-size: 12px; color: #888;">
                                    <div class="review-author">
                                        <?= mask_id($review['writer_id']) ?> (<?= mask_name($review['writer_name'] ?? '') ?>)
                                    </div>
                                    <div class="review-stats" style="display: flex; gap: 8px;">
                                        <span title="조회수"><i class="far fa-eye"></i> <?= number_format($review['views'] ?? 0) ?></span>
                                        <span title="댓글"><i class="far fa-comment-dots"></i> <?= number_format($review['comment_count'] ?? 0) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- 페이지네이션 -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?tab=<?= $tab ?>&sort=<?= $sort ?>&q=<?= urlencode($search_query) ?>&page=<?= $page - 1 ?>">이전</a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?tab=<?= $tab ?>&sort=<?= $sort ?>&q=<?= urlencode($search_query) ?>&page=<?= $i ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?tab=<?= $tab ?>&sort=<?= $sort ?>&q=<?= urlencode($search_query) ?>&page=<?= $page + 1 ?>">다음</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- 리뷰 상세 모달 -->
    <div id="reviewModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <button class="close-modal" onclick="closeReviewModal()">&times;</button>
            <div class="modal-body">
                <div class="modal-left">
                    <img id="modalImage" src="" alt="리뷰 이미지">
                </div>
                <div class="modal-right">
                    <div class="modal-header">
                        <div class="modal-product" id="modalProduct"></div>
                        <div class="modal-writer" id="modalWriter"></div>
                    </div>
                    <div class="modal-rating" id="modalRating"></div>
                    <div class="modal-text" id="modalText"></div>
                    
                    <div id="deleteReviewBtn" style="display:none; text-align:right; margin-bottom:10px;">
                        <button onclick="deleteReview()" style="background:#d9534f; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">삭제</button>
                    </div>
                    
                    <div class="comments-section">
                        <h3>댓글 <span id="commentCount">0</span></h3>
                        <div class="comment-list" id="commentList">
                            <!-- 댓글이 여기에 로드됩니다 -->
                        </div>
                        <div class="comment-form">
                            <input type="text" id="commentInput" placeholder="댓글을 입력하세요...">
                            <button onclick="submitComment()">등록</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            width: 900px;
            max-width: 95%;
            height: 600px;
            border-radius: 12px;
            position: relative;
            display: flex;
            overflow: hidden;
        }
        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 30px;
            cursor: pointer;
            z-index: 10;
            color: #333;
        }
        .modal-body {
            display: flex;
            width: 100%;
            height: 100%;
        }
        .modal-left {
            width: 50%;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-left img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .modal-right {
            width: 50%;
            padding: 30px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .modal-product {
            font-size: 14px;
            color: #888;
            margin-bottom: 5px;
        }
        .modal-writer {
            font-weight: bold;
            font-size: 16px;
        }
        .modal-rating {
            color: #ffc107;
            margin: 10px 0;
        }
        .modal-text {
            margin-bottom: 30px;
            line-height: 1.6;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .comments-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .comments-section h3 {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .comment-list {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 15px;
            max-height: 200px;
        }
        .comment-item {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .comment-item strong {
            margin-right: 5px;
        }
        .comment-date {
            font-size: 12px;
            color: #999;
            margin-left: 5px;
        }
        .comment-form {
            display: flex;
            gap: 10px;
        }
        .comment-form input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .comment-form button {
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .comment-form button:hover {
            background: #555;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentReviewId = null;

        function openReviewModal(element) {
            const data = JSON.parse(element.dataset.review);
            currentReviewId = data.id;

            // 조회수 증가
            fetch('increment_view.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ review_id: currentReviewId })
            }).then(() => {
                // 조회수 UI 업데이트 (선택적)
                const viewCountEl = element.querySelector('.review-stats span:first-child');
                if (viewCountEl) {
                    const currentViews = parseInt(viewCountEl.textContent.replace(/[^0-9]/g, '')) || 0;
                    viewCountEl.innerHTML = `<i class="far fa-eye"></i> ${(currentViews + 1).toLocaleString()}`;
                }
            });

            // 모달 내용 채우기
            document.getElementById('modalImage').src = data.photo || data.product_img || '';
            document.getElementById('modalProduct').textContent = data.product_name;
            document.getElementById('modalWriter').textContent = data.writer;
            document.getElementById('modalText').innerHTML = data.content;
            
            // 별점 생성
            let starsHtml = '';
            for(let i=1; i<=5; i++) {
                starsHtml += i <= data.star ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
            }
            document.getElementById('modalRating').innerHTML = starsHtml;

            // 삭제 버튼 표시 여부
            const deleteBtn = document.getElementById('deleteReviewBtn');
            if (data.can_delete) {
                deleteBtn.style.display = 'block';
            } else {
                deleteBtn.style.display = 'none';
            }

            // 댓글 로드
            loadComments();

            document.getElementById('reviewModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function loadComments() {
            $.get('get_comments.php', { review_id: currentReviewId }, function(comments) {
                const list = document.getElementById('commentList');
                document.getElementById('commentCount').textContent = comments.length;
                
                if (comments.length === 0) {
                    list.innerHTML = '<p style="color:#999; text-align:center; padding:20px;">첫 번째 댓글을 남겨보세요!</p>';
                    return;
                }

                let html = '';
                comments.forEach(c => {
                    html += `
                        <div class="comment-item">
                            <div>
                                <strong>${c.writer_name || '익명'}</strong>
                                <span class="comment-date">${c.reg_date.substring(0, 16)}</span>
                            </div>
                            <div style="margin-top:4px;">${c.comment_content}</div>
                        </div>
                    `;
                });
                list.innerHTML = html;
                list.scrollTop = list.scrollHeight;
            });
        }

        function submitComment() {
            const input = document.getElementById('commentInput');
            const content = input.value.trim();
            
            if (!content) {
                alert('댓글 내용을 입력해주세요.');
                return;
            }

            $.post('add_comment.php', {
                review_id: currentReviewId,
                content: content
            }, function(response) {
                if (response.status === 'success') {
                    input.value = '';
                    loadComments();
                } else {
                    alert(response.message || '댓글 등록에 실패했습니다.');
                }
            }, 'json');
        }

        function deleteReview() {
            if (!confirm('정말로 이 리뷰를 삭제하시겠습니까?')) return;
            
            $.post('delete_review.php', { review_id: currentReviewId }, function(response) {
                if (response.status === 'success') {
                    alert('리뷰가 삭제되었습니다.');
                    location.reload();
                } else {
                    alert(response.message || '삭제 실패');
                }
            }, 'json');
        }

        // 모달 외부 클릭 시 닫기
        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });
    </script>

    <?php require_once __DIR__ . '/inc/footer.php'; ?>
</body>
</html>
