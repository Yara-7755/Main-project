<?php
session_start();
require_once "classes/Skill.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$skill  = new Skill();
$skills = $skill->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skills Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dash-page">
<div class="dash-main">

    <!-- Top Bar -->
    <div class="dash-topbar">
        <h1 class="dash-title">🎯 <span>Skills</span></h1>
        <a href="dashboard.php" class="btn-logout">← Back</a>
    </div>

    <!-- Feedback -->
    <div id="skillFeedback" class="form-feedback"></div>

    <!-- Skills Table -->
    <div class="messages-table-wrap">
        <table class="messages-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Icon</th>
                <th>Name</th>
                <th>Description</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="skillsTableBody">
            <?php if (count($skills) == 0) { ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#9b9b9b;">
                        No skills yet.
                    </td>
                </tr>
            <?php } else { ?>
                <?php foreach ($skills as $row) { ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <img src="<?php echo $row['icon']; ?>"
                                 alt="<?php echo $row['name']; ?>"
                                 style="width:40px; height:40px; object-fit:contain;" />
                        </td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <div class="skill-bar-wrap" style="width:100px;">
                                <div class="skill-bar" style="width:<?php echo $row['level']; ?>%;"></div>
                            </div>
                            <?php echo $row['level']; ?>%
                        </td>
                        <td>
                            <button class="btn-edit"
                                    onclick="openEdit(
                                    <?php echo $row['id']; ?>,
                                        '<?php echo addslashes($row['icon']); ?>',
                                        '<?php echo addslashes($row['name']); ?>',
                                        '<?php echo addslashes($row['description']); ?>',
                                    <?php echo $row['level']; ?>
                                        )">
                                ✏️ Edit
                            </button>
                            <button class="btn-delete"
                                    onclick="deleteSkill(<?php echo $row['id']; ?>)">
                                🗑️ Delete
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h2 class="modal-title">✏️ Edit <span>Skill</span></h2>

            <input type="hidden" id="editId" />
            <input class="form-field" type="text"   id="editIcon"  placeholder="Image URL" />
            <input class="form-field" type="text"   id="editName"  placeholder="Skill Name" />
            <input class="form-field" type="text"   id="editDesc"  placeholder="Description" />
            <input class="form-field" type="number" id="editLevel" placeholder="Level (0-100)" min="0" max="100" />

            <div class="modal-actions">
                <button class="btn-send"   onclick="saveEdit()">Save Changes</button>
                <button class="btn-cancel" onclick="closeEdit()">Cancel</button>
            </div>
        </div>
    </div>

</div>

<script>

    /* ── DELETE ── */
    function deleteSkill(id) {
        if (!confirm("Are you sure you want to delete this skill?")) {
            return;
        }

        var formData = new FormData();
        formData.append('id', id);

        var request = new XMLHttpRequest();
        request.open('POST', 'delete_skill.php');

        request.onload = function() {
            var result = JSON.parse(request.responseText);

            if (result.success == true) {
                // احذف الصف من الجدول بدون reload
                document.getElementById('row-' + id).remove();
                showFeedback('success', '✅ Skill deleted successfully!');
            } else {
                showFeedback('error', '⚠️ ' + result.message);
            }
        };

        request.send(formData);
    }

    /* ── OPEN EDIT MODAL ── */
    function openEdit(id, icon, name, desc, level) {
        // ملي الحقول ببيانات الـ skill
        document.getElementById('editId').value    = id;
        document.getElementById('editIcon').value  = icon;
        document.getElementById('editName').value  = name;
        document.getElementById('editDesc').value  = desc;
        document.getElementById('editLevel').value = level;

        // افتح الـ modal
        document.getElementById('editModal').style.display = 'flex';
    }

    /* ── CLOSE EDIT MODAL ── */
    function closeEdit() {
        document.getElementById('editModal').style.display = 'none';
    }

    /* ── SAVE EDIT ── */
    function saveEdit() {
        var id    = document.getElementById('editId').value;
        var icon  = document.getElementById('editIcon').value;
        var name  = document.getElementById('editName').value;
        var desc  = document.getElementById('editDesc').value;
        var level = document.getElementById('editLevel').value;

        if (icon == "" || name == "" || desc == "" || level == "") {
            showFeedback('error', '⚠️ Please fill in all fields.');
            return;
        }

        var formData = new FormData();
        formData.append('id',    id);
        formData.append('icon',  icon);
        formData.append('name',  name);
        formData.append('desc',  desc);
        formData.append('level', level);

        var request = new XMLHttpRequest();
        request.open('POST', 'update_skill.php');

        request.onload = function() {
            var result = JSON.parse(request.responseText);

            if (result.success == true) {
                closeEdit();
                showFeedback('success', '✅ Skill updated successfully!');
                // reload الصفحة بعد ثانيتين عشان تظهر التغييرات
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showFeedback('error', '⚠️ ' + result.message);
            }
        };

        request.send(formData);
    }

    /* ── SHOW FEEDBACK ── */
    function showFeedback(type, message) {
        var feedback = document.getElementById('skillFeedback');
        feedback.className   = 'form-feedback ' + type;
        feedback.textContent = message;

        setTimeout(function() {
            feedback.className = 'form-feedback';
        }, 3000);
    }

</script>

</body>
</html>