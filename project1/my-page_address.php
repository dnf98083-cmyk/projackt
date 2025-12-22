<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];

// 주소 목록 조회
$addresses = db_select("SELECT * FROM delivery_address WHERE member_id = ? ORDER BY is_default DESC, created_at DESC", array($member_id));

function bust($relPath) {
    $abs = __DIR__ . '/' . ltrim($relPath, '/');
    $ver = is_file($abs) ? filemtime($abs) : time();
    return $relPath . '?v=' . $ver;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>배송지 관리 - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
</head>
<body>
    <?php require_once __DIR__ . "/inc/header.php"; ?>

    <main class="main_wrapper">
        <div class="mypage-wrapper">
            <?php require_once("inc/mypage_sidebar.php"); ?>

            <section class="mypage-content">
                <div class="page-header" style="border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 24px; font-weight: bold; margin: 0;">배송지 관리</h2>
                    <button type="button" onclick="openAddressModal()" style="background: #333; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">+ 새 배송지 추가</button>
                </div>

                <div class="address-list">
                    <?php if (empty($addresses)): ?>
                        <div class="no-data" style="text-align: center; padding: 50px 0; color: #888; border-bottom: 1px solid #eee;">
                            등록된 배송지가 없습니다.
                        </div>
                    <?php else: ?>
                        <?php foreach ($addresses as $addr): ?>
                            <div class="address-item" style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 15px; position: relative; <?= $addr['is_default'] ? 'border-color: #e60000; background: #fff5f5;' : '' ?>">
                                <?php if ($addr['is_default']): ?>
                                    <span style="position: absolute; top: 20px; right: 20px; background: #e60000; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">기본 배송지</span>
                                <?php endif; ?>
                                
                                <div style="margin-bottom: 10px;">
                                    <strong style="font-size: 18px; margin-right: 10px;"><?= htmlspecialchars($addr['recipient_name']) ?></strong>
                                    <span style="color: #666;"><?= htmlspecialchars($addr['recipient_phone']) ?></span>
                                </div>
                                <div style="color: #333; margin-bottom: 15px;">
                                    <?= htmlspecialchars($addr['address']) ?> <?= htmlspecialchars($addr['address_detail']) ?>
                                </div>
                                <div class="actions" style="display: flex; gap: 10px;">
                                    <?php if (!$addr['is_default']): ?>
                                        <form action="address_action.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="mode" value="set_default">
                                            <input type="hidden" name="id" value="<?= $addr['id'] ?>">
                                            <button type="submit" style="padding: 6px 12px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">기본으로 설정</button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="address_action.php" method="POST" style="display: inline;" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                                        <input type="hidden" name="mode" value="delete">
                                        <input type="hidden" name="id" value="<?= $addr['id'] ?>">
                                        <button type="submit" style="padding: 6px 12px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">삭제</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- Address Modal -->
    <div id="addressModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; width: 500px; max-width: 90%;">
            <h3 style="margin-top: 0; margin-bottom: 20px;">새 배송지 추가</h3>
            <form action="address_action.php" method="POST">
                <input type="hidden" name="mode" value="insert">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">받는 사람</label>
                    <input type="text" name="recipient_name" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">휴대폰 번호</label>
                    <input type="text" name="recipient_phone" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="010-0000-0000">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">주소</label>
                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                        <input type="text" id="postcode" placeholder="우편번호" readonly style="width: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <button type="button" onclick="execDaumPostcode()" style="padding: 8px 12px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer;">주소 검색</button>
                    </div>
                    <input type="text" name="address" id="address" required readonly style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 5px;" placeholder="기본 주소">
                    <input type="text" name="address_detail" id="detailAddress" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="상세 주소">
                </div>
                <div style="margin-bottom: 20px;">
                    <label>
                        <input type="checkbox" name="is_default" value="1"> 기본 배송지로 설정
                    </label>
                </div>
                <div style="text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeAddressModal()" style="padding: 10px 20px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">취소</button>
                    <button type="submit" style="padding: 10px 20px; background: #e60000; color: white; border: none; border-radius: 4px; cursor: pointer;">저장</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddressModal() {
            document.getElementById('addressModal').style.display = 'block';
        }
        function closeAddressModal() {
            document.getElementById('addressModal').style.display = 'none';
        }
        
        function execDaumPostcode() {
            new daum.Postcode({
                oncomplete: function(data) {
                    var addr = '';
                    if (data.userSelectedType === 'R') {
                        addr = data.roadAddress;
                    } else {
                        addr = data.jibunAddress;
                    }
                    document.getElementById('postcode').value = data.zonecode;
                    document.getElementById("address").value = addr;
                    document.getElementById("detailAddress").focus();
                }
            }).open();
        }
    </script>

    <?php require_once __DIR__ . "/inc/footer.php"; ?>
</body>
</html>