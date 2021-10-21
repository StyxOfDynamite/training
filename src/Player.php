<?php

namespace App;

use Exception;

/**
 * The player class cannot be instansiated so is abstract.
 * It holds a players name, score and current move.
 * It contains a move factory for returning the required move.
 */
abstract class Player
{
    protected $name;
    protected $move;
    protected $score;
    protected $moveFactory;
    protected $logger;
    private $bombCount;

    public function __construct(string $name, LoggerFactoryInterface $loggerFactory, MoveFactoryInterface $moveFactory)
    {
        $this->name = $name;
        $this->score = 0;
        $this->bombCount = 0;
        $this->moveFactory = $moveFactory;
        $this->loggerFactory = $loggerFactory;
    }

    /**
     * Setter for the current move.
     */
    public function setMove(Move $move)
    {
        if ($move instanceof Bomb) {
            $this->bombCount++;
        }

        if ($this->bombCount > 1) {
            throw new Exception('You can only play Bomb once per game');
        }

        $this->move = $move;
        $logger = $this->loggerFactory->provide('screen');
        $logger->log(sprintf("\t%s played %s\n", $this, $move));
    }

    /**
     * Getter for the current move.
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * addWin() is called when a player wins a round to increase a players score.
     */
    public function addWin()
    {
        $logger = $this->loggerFactory->provide('screen');
        $logger->log(sprintf("\t%s Wins!\n", $this));
        $this->score++;
    }

    /**
     * Getter for the current score.
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * toString() returns a simple representation of the the class as a string.
     * in this instance just the name
     */
    public function __toString()
    {
        return sprintf("%s", $this->name);
    }

    /**
     * an abstract method forces the method to be implemented in child classes.
     * so each type of player must implement a chooseMove() method.
     */
    abstract public function chooseMove();
}
