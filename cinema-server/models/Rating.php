<?php
class Rating extends Model {
    protected static string $table = "movie_ratings";
    private $rating;
    public function __construct(array $data) { $this->rating = $data['rating'] ?? null; }
    public function jsonSerialize(): array { return ['rating' => (float)$this->rating]; }
}
?>