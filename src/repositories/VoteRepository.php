<?php
namespace Repositories;

use Daos\VoteDaoInterface;
use Models\Vote;

class VoteRepository implements VoteRepositoryInterface {
    private VoteDaoInterface $voteDao;

    public function __construct(VoteDaoInterface $voteDao) {
        $this->voteDao = $voteDao;
    }

    public function getById($id) {
        return $this->voteDao->getById($id);
    }

    public function getByRoundId($roundId) {
        return $this->voteDao->getByRoundId($roundId);
    }
    
    public function insertVote(Vote $vote) : Vote {
        return $this->voteDao->insert($vote);
    }
    
    public function insertVotes($votes) : array {
        $arr = [];

        foreach($votes as $vote) {
            $arr[] = $this->insertVote($vote);
        }

        return $arr;
    }
    
    public function updateVote(Vote $vote) : Vote {
        if(!$this->voteDao->getById($vote->getId())) {
            return $this->insertVote($vote);
        }

        return $this->voteDao->update($vote);
    }
    
    public function updateVotes($votes) : array {
        $arr = [];

        foreach($votes as $vote) {
            $arr[] = $this->updateVote($vote);
        }

        return $arr;
    }
    
    public function deleteVote(Vote $vote) : Vote {
        return $this->voteDao->delete($vote);
    }
    
    public function deleteVotes($votes) : array {
        $arr = [];

        foreach($votes as $vote) {
            $arr[] = $this->deleteVote($vote);
        }

        return $arr;
    }
}
