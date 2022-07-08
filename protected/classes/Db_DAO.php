<?php
  class Db_DAO {
    public $db;
    public $SELECT = '*';
    public $TABLE = NULL;
    public $WHERE = NULL;
    public $ORDER = NULL;
    public $GROUP = NULL;
    public $LIMIT = NULL;

    function __construct($db, $table = NULL) {
      $this->db = $db;
      $this->TABLE = $table;
    }

// FETCH
    function fetchAll(array $parameters = [], $table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      $sql = 'SELECT '.$this->SELECT.'
              FROM '.$table;
      if ($this->WHERE)
        $sql .= "\n".'WHERE '.$this->WHERE;
      if ($this->GROUP)
        $sql .= "\n".'GROUP BY '.$this->GROUP;
      if ($this->ORDER)
        $sql .= "\n".'ORDER BY '.$this->ORDER;
      if ($this->LIMIT)
        $sql .= "\n".'LIMIT '.$this->LIMIT;
      //error_log( print_r( $sql, true ) );
      //error_log( print_r( $parameters, true ) );

      $st = $this->db->prepare($sql);
      $st->execute(array_values($parameters));
      return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    function fetch(array $parameters = [], $table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      $sql = 'SELECT '.$this->SELECT.'
              FROM '.$table;
      if ($this->WHERE)
        $sql .= "\n".'WHERE '.$this->WHERE;

      $st = $this->db->prepare($sql);
      $st->execute(array_values($parameters));
      return $st->fetch(PDO::FETCH_ASSOC);
    }

// DELETE
    function delete(array $match, $table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      $sql = 'DELETE FROM '.$table.'
              WHERE '.$this->WHERE;
      //error_log( print_r( $sql, true ) );
      //error_log( print_r( $match, true ) );

      $st = $this->db->prepare($sql);
      if ($st->execute(array_values($match)))
        return $st->rowCount();
      else
        return false;
    }

// DELETE all in table
    function deleteAll($table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      $sql = 'DELETE FROM '.$table;
      //error_log( print_r( $sql, true ) );
      //error_log( print_r( $match, true ) );

      $st = $this->db->prepare($sql);
      if ($st->execute([]))
        return $st->rowCount();
      else
        return false;
    }

// INSERT
    function insert(array $parameters, $table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      // Build SQL string
      $keys_str = '';
      $values_str = '';
      foreach (array_keys($parameters) as $key) {
       $keys_str .= $key.',';
       $values_str .= '?,';
      }
      $keys_str = rtrim($keys_str,',');
      $values_str = rtrim($values_str,',');

      $sql = 'INSERT INTO '.$table.' ('.$keys_str.')
              VALUES ('.$values_str.')';
      // error_log(print_r($sql, true));
      // error_log(print_r($parameters,true));

      $st = $this->db->prepare($sql);
      if ($st->execute(array_values($parameters))) {
        //error_log('success');
        return $st->rowCount();
      } else {
        //error_log('fail');
        return false;
      }
    }

// INSERT multiple rows
    function insertMulti(array $colNames, array $parameters, $table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      // Build SQL string
      $keys_str = implode(',', $colNames);
      $values_str = '(';
      foreach ($parameters[0] as $col) {
        $values_str .= '?,';
      }
      $values_str = rtrim($values_str,',') . ')';

      $full_values_str = '';
      $concatenated_parameters = [];
      foreach ($parameters as $row) {
        $full_values_str .= $values_str . ',';
        array_push($concatenated_parameters, ...array_values($row));
      }
      $full_values_str = rtrim($full_values_str,',');

      $sql = 'INSERT INTO '.$table.' ('.$keys_str.')
              VALUES '.$full_values_str;
      //var_dump($sql);
      //var_dump($parameters);

      $st = $this->db->prepare($sql);
      if ($st->execute(array_values($concatenated_parameters)))
        return $st->rowCount();
      else
        return false;
    }

// REPLACE
    function replace(array $parameters, $table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      // Build SQL string
      $keys_str = '';
      $values_str = '';
      $update_str = '';
      foreach (array_keys($parameters) as $key) {
       $keys_str .= $key.',';
       $values_str .= '?,';
       $update_str .= $key.'=?,';
      }
      $keys_str = rtrim($keys_str,',');
      $values_str = rtrim($values_str,',');
      $update_str = rtrim($update_str,',');

      $sql = 'INSERT INTO '.$table.' ('.$keys_str.')
              VALUES ('.$values_str.')
              ON DUPLICATE KEY
                UPDATE '.$update_str;
      $parameters = array_merge(array_values($parameters),array_values($parameters));
      $st = $this->db->prepare($sql);
      if ($st->execute($parameters))
        return $st->rowCount();
      else
        return false;
    }

// UPDATE
    function update(array $parameters, $match, $table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      // Remove invalid parameters
      $Names = $this->ColNames($table);
      //error_log( print_r( $Names, true ) );
      //error_log( print_r( $parameters, true ) );
      foreach (array_keys($parameters) as $key) {
        if (!in_array(str_replace('`','',$key),$Names))
          unset($parameters[$key]);
      }

      // Build SQL string
      $set_str = '';
      foreach (array_keys($parameters) as $key) {
       $set_str .= $key.'=?,';
      }
      $set_str = rtrim($set_str,',');

      $sql = 'UPDATE '.$table.'
              SET '.$set_str.'
              WHERE '.$this->WHERE;
      $parameters = array_values($parameters); // Prevent clash with $match
      $parameters = array_merge($parameters,$match);
      //error_log( print_r( $sql, true ) );
      //error_log( print_r( $parameters, true ) );
      $st = $this->db->prepare($sql);
      if ($st->execute(array_values($parameters)))
        return $st->rowCount();
    }

// AUTO-ENTRY: Insert
    function insertAutoEntry(array $parameters, $table = NULL) {
      global $USER;

      if (isset($USER['userKey'])) {
        $userKey = $USER['userKey'];
      } else {
        $userKey = 0;
      }

      $time = time();
      $parameters['createDate'] = $time;
      $parameters['createBy'] = $userKey;
      $parameters['modifyDate'] = $time;
      $parameters['modifyBy'] = $userKey;

      return $this->insert($parameters, $table);
    }

// AUTO-ENTRY: Update
function updateAutoEntry(array $parameters, $match, $table = NULL) {
      global $USER;

      if (isset($USER['userKey'])) {
        $userKey = $USER['userKey'];
      } else {
        $userKey = 0;
      }

      $parameters['modifyDate'] = time();
      $parameters['modifyBy'] = $userKey;
      return $this->update($parameters,$match, $table);
    }

// GET COLUMN NAMES
    function ColNames($table = NULL) {
      if (!$table)
        $table = $this->TABLE;

      // MariaDB / MySQL
      //$sql = '
        //SELECT column_name
        //FROM information_schema.columns
        //WHERE  table_name = ?';
      //$st = $this->db->prepare($sql);
      //$st->execute(array($table));
      //foreach ($st->fetchAll() as $col)
        //$result[] = $col[1];

      // Sqlite
      $sql = 'PRAGMA table_info('.$table.')';

      $st = $this->db->prepare($sql);
      $st->execute();
      foreach ($st->fetchAll() as $col)
        $result[] = $col[1];

      return $result;
    }

// Check if entry exists
    function entryExist(array $parameters, $table = NULL) {
      if (!$table)
        $table = $this->TABLE;
      // Remove invalid parameters
      $Names = $this->ColNames($table);
      foreach (array_keys($parameters) as $key) {
        if (!in_array($key,$Names))
          unset($parameters[$key]);
      }

      // Build SQL string
      $where_str = '';
      foreach (array_keys($parameters) as $key) {
       $where_str .= $key.' = ? AND ';
      }
      $where_str = rtrim($where_str,' AND ');

      $sql = 'SELECT *
              FROM '.$table.'
              WHERE '.$where_str;
      $st = $this->db->prepare($sql);
      $st->execute(array_values($parameters));
      $result = $st->fetch();

      if ($result)
        return TRUE;
      else
       return FALSE;
    }
  }
?>
