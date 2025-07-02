<?php
class Cast extends Model {
    protected static string $table = "movie_casts";
    private $actor_name, $character_name;
    public function __construct(array $data) {
        $this->actor_name = $data['actor_name'] ?? null;
        $this->character_name = $data['character_name'] ?? null;
    }
    public function jsonSerialize(): array { return ['actor_name' => $this->actor_name, 'character_name' => $this->character_name]; }
}
?>