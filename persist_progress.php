// persist_progress.php
public function updateProgress(int $userId, int $lessonId, string $status) {
    $sql = "INSERT INTO user_progress (user_id, lesson_id, status, updated_at) 
            VALUES (:uid, :lid, :status, NOW())
            ON DUPLICATE KEY UPDATE status = :status, updated_at = NOW()";
    
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        'uid' => $userId,
        'lid' => $lessonId,
        'status' => $status
    ]);
}