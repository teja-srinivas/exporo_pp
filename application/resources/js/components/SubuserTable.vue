<template>
  <data-table v-bind="$props" with-details>
    <template v-slot="{ row }">
      <div class="text-lg-center font-weight-bold border-bottom">
        Overhead Vergütung für diesen Partner
      </div>
      <table
        class="table table-sm bg-white shadow-sm table-sticky
               table-borderless table-striped table-fixed"
      >
        <thead>
          <tr>
            <th
              :width="32"
              :colspan="1"
            />
            <th>
              Typ
            </th>
            <th>
              Für
            </th>
            <th class="text-right">
              Wert
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="bonus in row.bonuses">
            <td
              :width="32"
              :colspan="1"
            />
            <td>
              {{ bonus.type_name }}
            </td>
            <td>
              {{ bonus.calculation_type }}
            </td>
            <td class="text-right">
              {{ bonus.value }} {{ bonus.sign }}
            </td>
          </tr>
        </tbody>
      </table>
    </template>
  </data-table>
</template>

<script>
export default {
  props: ['columns', 'rows'],
};
</script>
