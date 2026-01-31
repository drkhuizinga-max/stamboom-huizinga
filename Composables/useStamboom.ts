// composables/useStamboom.ts
export const useStamboomData = () => {
  return useState('stamboom-cache', () => 
    queryContent('personen').only(['id', 'naam', 'vader_id', 'moeder_id', '_path']).find()
  )
}
