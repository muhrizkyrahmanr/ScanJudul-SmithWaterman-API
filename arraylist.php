<?php


class ArrayList
{
    public $arrayList;

    /**
     * Constructor
     * Xây dựng một danh sách mới. Tham số $ arr là tùy chọn. Nếu đặt một ArrayList
     * với các phần tử trong mảng được tạo. Nếu không một danh sách trống được xây dựng.
     * @param arr - mảng một chiều (tùy chọn)
     **/
    public function ArrayList($arr = "")
    {
        if (is_array($arr) == true)
            $this->arrayList = $arr;
        else
            $this->arrayList = array();
    }

    /**
     * Thêm một phần tử vào cuối danh sách này.
     * @param $obj
     **/
    public function add($obj)
    {
        array_push($this->arrayList, $obj);
    }

    /**
     * Xóa tất cả các phần tử khỏi danh sách này.
     **/
    public function clear()
    {
        $this->arrayList = array();
    }

    /**
     * Trả về phần tử tại vị trí đã chỉ định trong danh sách này
     * @param $index
     **/
    public function get($index)
    {
        if ($this->isInteger($index) && $index < $this->size()) {
            return $this->arrayList[$index];

        } else {
            die("ERROR in ArrayList.get");
        }
    }


    /**
     * Kiểm tra nếu danh sách này không có phần tử nào.
     * @return boolean
     **/
    public function isEmpty()
    {
        if (count($this->arrayList) == 0) {
            return true;
        }
        return false;
    }

    /**
     * Loại bỏ phần tử ở vị trí đã chỉ định trong danh sách này.
     * @param $index
     **/
    public function remove($index)
    {
        if ($this->isInteger($index)) {
            $newArrayList = array();

            for ($i = 0; $i < $this->size(); $i++)
                if ($index != $i) $newArrayList[] = $this->get($i);

            $this->arrayList = $newArrayList;
        } else {
            die("ERROR in ArrayList.remove <br> Integer value required");
        }
    }

    /**
     * Trả về số phần tử trong danh sách này.
     * @return integer
     **/
    public function size()
    {
        return count($this->arrayList);
    }

    /**
     * Sắp xếp danh sách theo thứ tự bảng chữ cái.
     **/
    public function sort()
    {
        sort($this->arrayList);
    }


    /**
     * Trả về một mảng chứa tất cả các phần tử trong danh sách này theo đúng thứ tự.
     * @return array
     **/
    public function toArray()
    {
        return $this->arrayList;
    }

    /**
     * Trả về true nếu tham số chứa một giá trị số nguyên
     * @return boolean
     **/
    public function isInteger($toCheck) {
        return preg_match("/^[0-9]+$/", $toCheck);
    }

}
?>