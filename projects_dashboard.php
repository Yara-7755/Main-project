<?php
session_start();
require_once "classes/Project.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$project  = new Project();
$projects = $project->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dash-page">
<div class="dash-main">

    <div class="dash-topbar">
        <h1 class="dash-title">🚀 <span>Projects</span></h1>
        <a href="dashboard.php" class="btn-logout">← Back</a>
    </div>

    <div id="projectFeedback" class="form-feedback"></div>

    <div class="messages-table-wrap">
        <table class="messages-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Tech</th>
                <th>Link</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($projects) == 0) { ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#9b9b9b;">
                        No projects yet.
                    </td>
                </tr>
            <?php } else { ?>
                <?php foreach ($projects as $row) { ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><span class="tech-tag"><?php echo $row['tech']; ?></span></td>
                        <td>
                            <a href="<?php echo $row['link']; ?>"
                               target="_blank"
                               style="color:#e8a3b8;">
                                View →
                            </a>
                        </td>
                        <td>
                            <button class="btn-edit"
                                    onclick="openEdit(
                                    <?php echo $row['id']; ?>,
                                        '<?php echo addslashes($row['title']); ?>',
                                        '<?php echo addslashes($row['description']); ?>',
                                        '<?php echo addslashes($row['tech']); ?>',
                                        '<?php echo addslashes($row['link']); ?>'
                                        )">
                                ✏️ Edit
                            </button>
                            <button class="btn-delete"
                                    onclick="deleteProject(<?php echo $row['id']; ?>)">
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
            <h2 class="modal-title">✏️ Edit <span>Project</span></h2>

            <input type="hidden" id="editId" />
            <input class="form-field" type="text" id="editTitle" placeholder="Project Title" />
            <input class="form-field" type="text" id="editDesc"  placeholder="Description" />
            <input class="form-field" type="text" id="editTech"  placeholder="Tech e.g: HTML, PHP" />
            <input class="form-field" type="text" id="editLink"  placeholder="Project Link" />

            <div class="modal-actions">
                <button class="btn-send"   onclick="saveEdit()">Save Changes</button>
                <button class="btn-cancel" onclick="closeEdit()">Cancel</button>
            </div>
        </div>
    </div>

</div>

<script>
    function deleteProject(id) {
        if (!confirm("Are you sure you want to delete this project?")) {
            return;
        }

        var formData = new FormData();
        formData.append('id', id);

        var request = new XMLHttpRequest();
        request.open('POST', 'delete_project.php');

        request.onload = function() {
            var result = JSON.parse(request.responseText);

            if (result.success == true) {
                document.getElementById('row-' + id).remove();
                showFeedback('success', '✅ Project deleted!');
            } else {
                showFeedback('error', '⚠️ ' + result.message);
            }
        };

        request.send(formData);
    }

    function openEdit(id, title, desc, tech, link) {
        document.getElementById('editId').value    = id;
        document.getElementById('editTitle').value = title;
        document.getElementById('editDesc').value  = desc;
        document.getElementById('editTech').value  = tech;
        document.getElementById('editLink').value  = link;

        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEdit() {
        document.getElementById('editModal').style.display = 'none';
    }

    function saveEdit() {
        var id    = document.getElementById('editId').value;
        var title = document.getElementById('editTitle').value;
        var desc  = document.getElementById('editDesc').value;
        var tech  = document.getElementById('editTech').value;
        var link  = document.getElementById('editLink').value;

        if (title == "" || desc == "" || tech == "" || link == "") {
            showFeedback('error', '⚠️ Please fill in all fields.');
            return;
        }

        var formData = new FormData();
        formData.append('id',    id);
        formData.append('title', title);
        formData.append('desc',  desc);
        formData.append('tech',  tech);
        formData.append('link',  link);

        var request = new XMLHttpRequest();
        request.open('POST', 'update_project.php');

        request.onload = function() {
            var result = JSON.parse(request.responseText);

            if (result.success == true) {
                closeEdit();
                showFeedback('success', '✅ Project updated!');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showFeedback('error', '⚠️ ' + result.message);
            }
        };

        request.send(formData);
    }

    function showFeedback(type, message) {
        var feedback       = document.getElementById('projectFeedback');
        feedback.className = 'form-feedback ' + type;
        feedback.textContent = message;

        setTimeout(function() {
            feedback.className = 'form-feedback';
        }, 3000);
    }
</script>

</body>
</html>