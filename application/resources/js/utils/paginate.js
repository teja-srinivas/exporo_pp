export default function paginate(array, size, page) {
  page = Math.max(0, page - 1);
  return array.slice(page * size, (page + 1) * size);
}
