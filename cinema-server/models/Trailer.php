<?php
class Trailer extends Model {
    protected static string $table = "movie_trailers";
    private $trailer_url;
    public function __construct(array $data) { $this->trailer_url = $data['trailer_url'] ?? null; }
    public function jsonSerialize(): array { return ['trailer_url' => $this->trailer_url]; }
}
?>