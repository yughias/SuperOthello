SET GLOBAL event_scheduler := 1;

DROP EVENT IF EXISTS decrease_timer;
CREATE EVENT decrease_timer
    ON SCHEDULE
      EVERY 1 SECOND
    DO
      UPDATE games SET timer = timer-1 WHERE timer <> -10;

DROP EVENT IF EXISTS remove_abbandoned_games;
DELIMITER //
CREATE EVENT remove_abbandoned_games
    ON SCHEDULE
      EVERY 1 SECOND
    DO
      BEGIN
        UPDATE stats
        SET online_loss = online_loss+1
        WHERE user IN (
          SELECT black
          FROM games
          WHERE timer = -10 AND currentPlayer = "black"
        ) OR user IN (
                        SELECT white
                        FROM games
                        WHERE timer = -10 AND currentPlayer = "white"
        );

        UPDATE stats
        SET online_win = online_win+1
        WHERE user IN (
          SELECT black
          FROM games
          WHERE timer = -10 AND currentPlayer = "white"
        ) OR user IN (
                        SELECT white
                        FROM games
                        WHERE timer = -10 AND currentPlayer = "black"
        );

        DELETE 
        FROM games
        WHERE timer = -10;
      END //
DELIMITER ;
